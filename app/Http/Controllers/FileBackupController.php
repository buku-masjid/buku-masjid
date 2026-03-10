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
    private const MAX_FILE_SIZE = 50 * 1024 * 1024; // 50MB

    public function index(): View
    {
        $this->authorize('manage_file_backup');

        $backupDir = storage_path('app/'.self::BACKUP_PATH);

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
        $zipFileName = $fileName.'.zip';
        $backupDir = storage_path('app/'.self::BACKUP_PATH);
        $publicDir = storage_path('app/'.self::PUBLIC_PATH);
        $zipPath = $backupDir.'/'.$zipFileName;

        if (!File::exists($backupDir)) {
            File::makeDirectory($backupDir, 0755, true);
        }

        $publicFiles = File::allFiles($publicDir);
        $fileChecksums = [];

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            foreach ($publicFiles as $file) {
                $relativePath = $file->getRelativePathname();
                $fileChecksums[$relativePath] = hash_file('sha256', $file->getPathname());
                $zip->addFile($file->getPathname(), $relativePath);
            }
            $zip->close();

            $zip->open($zipPath);
            $manifest = [
                'created_at' => date('Y-m-d H:i:s'),
                'files' => $fileChecksums,
            ];
            $zip->addFromString('manifest.json', json_encode($manifest, JSON_PRETTY_PRINT));
            $zip->close();
        }

        flash(__('file_backup.created', ['filename' => $zipFileName]), 'success');

        return redirect()->route('file_backups.index');
    }

    public function destroy(string $fileName): RedirectResponse
    {
        $this->authorize('manage_file_backup');

        $backupDir = storage_path('app/'.self::BACKUP_PATH);
        $filePath = $backupDir.'/'.$fileName;

        if (File::exists($filePath)) {
            File::delete($filePath);
        }

        flash(__('file_backup.deleted', ['filename' => $fileName]), 'warning');

        return redirect()->route('file_backups.index');
    }

    public function download(string $fileName): StreamedResponse
    {
        $this->authorize('manage_file_backup');

        return Storage::disk('local')->download(self::BACKUP_PATH.'/'.$fileName);
    }

    public function restore(Request $request, string $fileName): RedirectResponse
    {
        $this->authorize('manage_file_backup');

        $backupDir = storage_path('app/'.self::BACKUP_PATH);
        $zipPath = $backupDir.'/'.$fileName;
        $publicDir = storage_path('app/'.self::PUBLIC_PATH);

        if (!File::exists($zipPath)) {
            flash(__('file_backup.restore_failed', ['filename' => $fileName]), 'danger');
            return redirect()->route('file_backups.index');
        }

        $validationResult = $this->validateBackupChecksum($zipPath);
        if (!$validationResult['valid']) {
            flash(__('file_backup.restore_failed_invalid'), 'danger');
            return redirect()->route('file_backups.index');
        }

        if (!$this->safeExtract($zipPath, $publicDir)) {
            flash(__('file_backup.restore_failed_traversal'), 'danger');
            return redirect()->route('file_backups.index');
        }

        flash(__('file_backup.restored', ['filename' => $fileName]), 'success');

        return redirect()->route('file_backups.index');
    }

    private function validateBackupChecksum(string $zipPath): array
    {
        try {
            $zip = new ZipArchive();
            if ($zip->open($zipPath) !== true) {
                return ['valid' => false, 'checksum' => ''];
            }

            $manifestContent = $zip->getFromName('manifest.json');
            $zip->close();

            if (!$manifestContent) {
                return ['valid' => false, 'checksum' => ''];
            }

            $manifest = json_decode($manifestContent, true);
            $storedChecksums = $manifest['files'] ?? [];

            if (empty($storedChecksums)) {
                return ['valid' => false, 'checksum' => ''];
            }

            $zip = new ZipArchive();
            if ($zip->open($zipPath) !== true) {
                return ['valid' => false, 'checksum' => ''];
            }

            for ($i = 0; $i < $zip->numFiles; $i++) {
                $entryName = $zip->getNameIndex($i);
                if ($entryName === 'manifest.json') {
                    continue;
                }

                $content = $zip->getFromIndex($i);
                $actualChecksum = hash('sha256', $content);

                if (!isset($storedChecksums[$entryName])) {
                    $zip->close();
                    return ['valid' => false, 'checksum' => ''];
                }

                if ($actualChecksum !== $storedChecksums[$entryName]) {
                    $zip->close();
                    return ['valid' => false, 'checksum' => ''];
                }
            }

            $zip->close();

            return ['valid' => true, 'checksum' => ''];
        } catch (\Exception $e) {
            return ['valid' => false, 'checksum' => ''];
        }
    }

    private function safeExtract(string $zipPath, string $extractPath): bool
    {
        $zip = new ZipArchive();
        if ($zip->open($zipPath) !== true) {
            return false;
        }

        $baseDir = realpath($extractPath);
        if ($baseDir === false) {
            if (!File::makeDirectory($extractPath, 0755, true)) {
                $zip->close();
                return false;
            }
            $baseDir = realpath($extractPath);
        }

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $entryName = $zip->getNameIndex($i);
            if ($entryName === 'manifest.json') {
                continue;
            }

            $entryDir = dirname($entryName);
            if ($entryDir === '.') {
                $entryDir = '';
            }

            $fullPath = $extractPath.'/'.$entryName;
            $resolvedPath = realpath($fullPath);

            if ($resolvedPath !== false && !str_starts_with($resolvedPath, $baseDir)) {
                $zip->close();
                return false;
            }

            if ($entryDir !== '' && !is_dir($extractPath.'/'.$entryDir)) {
                continue;
            }

            $checkPath = $entryDir === '' ? $fullPath : $extractPath.'/'.$entryDir;
            $realCheckPath = realpath($checkPath);
            if ($realCheckPath !== false && !str_starts_with($realCheckPath, $baseDir)) {
                $zip->close();
                return false;
            }
        }

        $zip->extractTo($extractPath);
        $zip->close();

        return true;
    }

    public function upload(Request $request): RedirectResponse
    {
        $this->authorize('manage_file_backup');

        $validatedPayload = $request->validate([
            'backup_file' => 'required|file|mimes:zip|max:'.(self::MAX_FILE_SIZE / 1024),
        ]);

        $file = $validatedPayload['backup_file'];
        $fileName = $file->getClientOriginalName();
        $backupDir = storage_path('app/'.self::BACKUP_PATH);
        $tempPath = $file->getPathname();

        $validationResult = $this->validateBackupChecksum($tempPath);
        if (!$validationResult['valid']) {
            flash(__('file_backup.upload_invalid'), 'danger');
            return redirect()->route('file_backups.index');
        }

        if (!File::exists($backupDir)) {
            File::makeDirectory($backupDir, 0755, true);
        }

        $file->move($backupDir, $fileName);

        flash(__('file_backup.uploaded', ['filename' => $fileName]), 'success');

        return redirect()->route('file_backups.index');
    }
}
