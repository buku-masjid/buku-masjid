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

        $backupFiles = Storage::disk('local')->files(self::BACKUP_PATH);
        $backupFiles = array_filter($backupFiles, function ($file) {
            return pathinfo($file, PATHINFO_EXTENSION) === 'zip';
        });

        $backups = [];
        foreach ($backupFiles as $file) {
            $backups[] = [
                'filename' => basename($file),
                'size' => Storage::disk('local')->size($file),
                'modified' => Storage::disk('local')->lastModified($file),
            ];
        }

        usort($backups, function ($a, $b) {
            return -1 * strcmp($a['modified'], $b['modified']);
        });

        return view('file_backups.index', compact('backups'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('manage_file_backup');

        $validatedPayload = $request->validate([
            'file_name' => 'nullable|max:30|regex:/^[\w._-]+$/',
        ]);

        $fileName = $validatedPayload['file_name'] ?: date('Y-m-d_Hi');
        $zipFileName = $fileName.'.zip';

        $tempDir = sys_get_temp_dir();
        $tempZip = $tempDir.'/'.$zipFileName;

        $publicFiles = Storage::disk('local')->allFiles(self::PUBLIC_PATH);
        $fileChecksums = [];

        $zip = new ZipArchive();
        if ($zip->open($tempZip, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            foreach ($publicFiles as $file) {
                $relativePath = str_replace(self::PUBLIC_PATH.'/', '', $file);
                $content = Storage::disk('local')->get($file);
                $fileChecksums[$relativePath] = hash('sha256', $content);
                $zip->addFromString($relativePath, $content);
            }
            $zip->close();

            $zip->open($tempZip);
            $manifest = [
                'created_at' => date('Y-m-d H:i:s'),
                'files' => $fileChecksums,
            ];
            $zip->addFromString('manifest.json', json_encode($manifest, JSON_PRETTY_PRINT));
            $zip->close();
        }

        Storage::disk('local')->put(self::BACKUP_PATH.'/'.$zipFileName, file_get_contents($tempZip));
        unlink($tempZip);

        flash(__('file_backup.created', ['filename' => $zipFileName]), 'success');

        return redirect()->route('file_backups.index');
    }

    public function destroy(string $fileName): RedirectResponse
    {
        $this->authorize('manage_file_backup');

        Storage::disk('local')->delete(self::BACKUP_PATH.'/'.$fileName);

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

        $zipPath = self::BACKUP_PATH.'/'.$fileName;

        if (!Storage::disk('local')->exists($zipPath)) {
            flash(__('file_backup.restore_failed', ['filename' => $fileName]), 'danger');
            return redirect()->route('file_backups.index');
        }

        $tempDir = sys_get_temp_dir();
        $tempZip = $tempDir.'/'.$fileName;

        file_put_contents($tempZip, Storage::disk('local')->get($zipPath));

        $validationResult = $this->validateBackupChecksum($tempZip);
        if (!$validationResult['valid']) {
            unlink($tempZip);
            flash(__('file_backup.restore_failed_invalid'), 'danger');
            return redirect()->route('file_backups.index');
        }

        if (!$this->extractToPublic($tempZip)) {
            unlink($tempZip);
            flash(__('file_backup.restore_failed_traversal'), 'danger');
            return redirect()->route('file_backups.index');
        }

        unlink($tempZip);

        flash(__('file_backup.restored', ['filename' => $fileName]), 'success');

        return redirect()->route('file_backups.index');
    }

    private function extractToPublic(string $tempZip): bool
    {
        $zip = new ZipArchive();
        if ($zip->open($tempZip) !== true) {
            return false;
        }

        $publicDir = Storage::disk('local')->path(self::PUBLIC_PATH);

        $baseDir = realpath($publicDir);
        if ($baseDir === false) {
            $baseDir = $publicDir;
        }

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $entryName = $zip->getNameIndex($i);
            if ($entryName === 'manifest.json') {
                continue;
            }

            $fullPath = $publicDir.'/'.$entryName;
            $resolvedPath = realpath($fullPath);

            if ($resolvedPath !== false && !str_starts_with($resolvedPath, $baseDir)) {
                $zip->close();
                return false;
            }
        }

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $entryName = $zip->getNameIndex($i);
            if ($entryName === 'manifest.json') {
                continue;
            }

            $content = $zip->getFromName($entryName);
            Storage::disk('local')->put(self::PUBLIC_PATH.'/'.$entryName, $content);
        }

        $zip->close();

        return true;
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

    public function upload(Request $request): RedirectResponse
    {
        $this->authorize('manage_file_backup');

        $validatedPayload = $request->validate([
            'backup_file' => 'required|file|mimes:zip|max:'.(self::MAX_FILE_SIZE / 1024),
        ]);

        $file = $validatedPayload['backup_file'];
        $fileName = $file->getClientOriginalName();
        $tempPath = $file->getPathname();

        $validationResult = $this->validateBackupChecksum($tempPath);
        if (!$validationResult['valid']) {
            flash(__('file_backup.upload_invalid'), 'danger');
            return redirect()->route('file_backups.index');
        }

        Storage::disk('local')->put(self::BACKUP_PATH.'/'.$fileName, file_get_contents($tempPath));

        flash(__('file_backup.uploaded', ['filename' => $fileName]), 'success');

        return redirect()->route('file_backups.index');
    }
}
