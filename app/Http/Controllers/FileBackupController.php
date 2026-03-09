<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;
use ZipArchive;

class FileBackupController extends Controller
{
    private const BACKUP_PATH = 'backup/files';
    private const PUBLIC_PATH = 'public';

    public function index(): View
    {
        $this->authorize('manage_file_backup');

        $backupDir = storage_path('app/' . self::BACKUP_PATH);

        if (!File::exists($backupDir)) {
            $backups = [];
        } else {
            $backups = File::allFiles($backupDir);
            $this->sortFilesByModifiedTimeDesc($backups);
        }

        return view('file_backups.index', compact('backups'));
    }

    private function sortFilesByModifiedTimeDesc(array &$backups): void
    {
        usort($backups, function ($a, $b) {
            return -1 * strcmp($a->getMTime(), $b->getMTime());
        });
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('manage_file_backup');

        $validatedPayload = $request->validate([
            'file_name' => 'nullable|max:30|regex:/^[\w._-]+$/',
        ]);

        $fileName = $validatedPayload['file_name'] ?: date('Y-m-d_Hi');
        $zipFileName = $fileName . '.zip';
        $backupDir = storage_path('app/' . self::BACKUP_PATH);
        $publicDir = storage_path('app/' . self::PUBLIC_PATH);
        $zipPath = $backupDir . '/' . $zipFileName;

        if (!File::exists($backupDir)) {
            File::makeDirectory($backupDir, 0755, true);
        }

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            $publicFiles = File::allFiles($publicDir);
            foreach ($publicFiles as $file) {
                $relativePath = $file->getRelativePathname();
                $zip->addFile($file->getPathname(), $relativePath);
            }
            $zip->close();
        }

        flash(__('file_backup.created', ['filename' => $zipFileName]), 'success');

        return redirect()->route('file_backups.index');
    }

    public function destroy(string $fileName): RedirectResponse
    {
        $this->authorize('manage_file_backup');

        $backupDir = storage_path('app/' . self::BACKUP_PATH);
        $filePath = $backupDir . '/' . $fileName;

        if (File::exists($filePath)) {
            File::delete($filePath);
        }

        flash(__('file_backup.deleted', ['filename' => $fileName]), 'warning');

        return redirect()->route('file_backups.index');
    }

    public function download(string $fileName): StreamedResponse
    {
        $this->authorize('manage_file_backup');

        return Storage::disk('local')->download(self::BACKUP_PATH . '/' . $fileName);
    }

    public function restore(Request $request, string $fileName): RedirectResponse
    {
        $this->authorize('manage_file_backup');

        $backupDir = storage_path('app/' . self::BACKUP_PATH);
        $zipPath = $backupDir . '/' . $fileName;
        $publicDir = storage_path('app/' . self::PUBLIC_PATH);

        if (!File::exists($zipPath)) {
            flash(__('file_backup.restore_failed', ['filename' => $fileName]), 'danger');
            return redirect()->route('file_backups.index');
        }

        $zip = new ZipArchive();
        if ($zip->open($zipPath) === true) {
            $zip->extractTo($publicDir);
            $zip->close();
            flash(__('file_backup.restored', ['filename' => $fileName]), 'success');
        } else {
            flash(__('file_backup.restore_failed', ['filename' => $fileName]), 'danger');
        }

        return redirect()->route('file_backups.index');
    }
}
