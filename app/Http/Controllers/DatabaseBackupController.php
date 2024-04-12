<?php

namespace App\Http\Controllers;

use BackupManager\Filesystems\Destination;
use BackupManager\Manager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DatabaseBackupController extends Controller
{
    public function index(): View
    {
        $this->authorize('manage_database_backup');

        if (Storage::disk('local')->missing('backup/db')) {
            $backups = [];
        } else {
            $backups = File::allFiles(Storage::disk('local')->path('backup/db'));

            $this->sortFilesByModifiedTimeDesc($backups);
        }

        return view('database_backups.index', compact('backups'));
    }

    private function sortFilesByModifiedTimeDesc(array &$backups): void
    {
        usort($backups, function ($a, $b) {
            return -1 * strcmp($a->getMTime(), $b->getMTime());
        });
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('manage_database_backup');

        $validatedPayload = $request->validate([
            'file_name' => 'nullable|max:30|regex:/^[\w._-]+$/',
        ]);

        $manager = app()->make(Manager::class);
        $fileName = $validatedPayload['file_name'] ?: date('Y-m-d_Hi');

        $manager->makeBackup()->run('mysql', [
            new Destination('local', 'backup/db/'.$fileName),
        ], 'gzip');

        flash(__('database_backup.created', ['filename' => $fileName.'.gz']), 'success');

        return redirect()->route('database_backups.index');
    }

    public function destroy(string $fileName): RedirectResponse
    {
        $this->authorize('manage_database_backup');

        if (Storage::disk('local')->exists('backup/db/'.$fileName)) {
            Storage::disk('local')->delete('backup/db/'.$fileName);
        }

        flash(__('database_backup.deleted', ['filename' => $fileName]), 'warning');

        return redirect()->route('database_backups.index');
    }

    public function download(string $fileName): StreamedResponse
    {
        $this->authorize('manage_database_backup');

        return Storage::disk('local')->download('backup/db/'.$fileName);
    }

    public function restore(string $fileName): RedirectResponse
    {
        $this->authorize('manage_database_backup');

        $manager = app()->make(Manager::class);
        $manager->makeRestore()->run('local', 'backup/db/'.$fileName, 'mysql', 'gzip');

        flash(__('database_backup.restored', ['filename' => $fileName]), 'success');

        return redirect()->route('database_backups.index');
    }

    public function upload(Request $request): RedirectResponse
    {
        $this->authorize('manage_database_backup');

        $validatedPayload = $request->validate([
            'backup_file' => 'required|file|mimes:gz',
        ]);

        $file = $validatedPayload['backup_file'];
        $fileName = $file->getClientOriginalName();
        $file->storeAs('backup/db', $fileName, 'local');

        flash(__('database_backup.uploaded', ['filename' => $fileName]), 'success');

        return redirect()->route('database_backups.index');
    }
}
