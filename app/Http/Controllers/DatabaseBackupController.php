<?php

namespace App\Http\Controllers;

use BackupManager\Filesystems\Destination;
use BackupManager\Manager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DatabaseBackupController extends Controller
{
    public function index()
    {
        if (Storage::missing('backup/db')) {
            $backups = [];
        } else {
            $backups = File::allFiles(Storage::path('backup/db'));

            // Sort files by modified time DESC
            usort($backups, function ($a, $b) {
                return -1 * strcmp($a->getMTime(), $b->getMTime());
            });
        }

        return view('database_backups.index', compact('backups'));
    }

    public function store(Request $request)
    {
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

    public function destroy($fileName)
    {
        if (Storage::exists('backup/db/'.$fileName)) {
            Storage::delete('backup/db/'.$fileName);
        }

        flash(__('database_backup.deleted', ['filename' => $fileName]), 'warning');

        return redirect()->route('database_backups.index');
    }

    public function download($fileName)
    {
        return response()->download(Storage::path('backup/db/'.$fileName));
    }

    public function restore($fileName)
    {
        $manager = app()->make(Manager::class);
        $manager->makeRestore()->run('local', 'backup/db/'.$fileName, 'mysql', 'gzip');

        flash(__('database_backup.restored', ['filename' => $fileName]), 'success');

        return redirect()->route('database_backups.index');
    }

    public function upload(Request $request)
    {
        $validatedPayload = $request->validate([
            'backup_file' => 'required|file|mimes:gz',
        ], [
            'backup_file.mimetypes' => 'Invalid file type, must be <strong>.gz</strong> file',
        ]);

        $file = $validatedPayload['backup_file'];
        $fileName = $file->getClientOriginalName();
        $file->storeAs('backup/db', $fileName);

        flash(__('database_backup.uploaded', ['filename' => $fileName]), 'success');

        return redirect()->route('database_backups.index');
    }
}
