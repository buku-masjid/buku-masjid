# Secure File Backup Feature

## Overview

This document describes the file backup system for user-uploaded files stored in `storage/app/public`. The system provides secure backup and restore functionality with checksum validation to prevent tampering.

## Features

- **List backups**: View all available backup files
- **Create backup**: Zip all files in `storage/app/public` with manifest
- **Download backup**: Download backup ZIP file
- **Restore backup**: Extract backup ZIP to `storage/app/public`
- **Upload backup**: Upload previously created backup ZIP

## Route

- `/file_backups` - Resource routes (index, store, destroy, download, restore, upload)

## Authorization

Only Admin users can access this feature. The permission is defined in `app/Providers/AuthServiceProvider.php`:

```php
Gate::define('manage_file_backup', function (User $user) {
    return in_array($user->role_id, [User::ROLE_ADMIN]);
});
```

## Security Implementation

### 1. Manifest-Based Validation

Each backup ZIP contains a `manifest.json` file with SHA256 checksums for all files:

```json
{
    "created_at": "2026-03-10 12:00:00",
    "files": {
        "files/2026/03/image1.jpg": "abc123...",
        "files/2026/03/document.pdf": "def456..."
    }
}
```

### 2. Per-File Checksum Validation

Instead of validating the ZIP file structure, we validate each file's content:

1. For each file in the ZIP, calculate SHA256 hash
2. Compare with the checksum stored in manifest.json
3. If any file fails validation, reject the backup

This approach is:
- **Reliable**: ZIP creation order/compression doesn't affect validation
- **Secure**: Detects any modification to files inside the ZIP
- **Efficient**: Can validate without full extraction

### 3. Upload Security

When uploading a backup:

1. Open the ZIP and read manifest.json
2. For each file entry, calculate SHA256 and compare with manifest
3. If all checksums match, accept the upload

This ensures:
- Only backups created by the system can be uploaded (has valid manifest)
- Modified or corrupted backups are rejected
- Extra files added to ZIP are rejected

### 4. Restore Security

When restoring a backup:

1. Copy ZIP from storage to temp directory
2. Validate file checksums against manifest
3. Apply Zip Slip protection (path traversal prevention)
4. Extract files to public directory using Storage facade

### 5. Zip Slip Protection

The `extractToPublic()` method prevents path traversal attacks by:

- Getting the real path of the target directory
- For each entry in ZIP, verifying the resolved path stays within the target directory
- Rejecting any entries that would escape the target directory

### 6. Size Limit

Maximum upload size: 50MB (configurable via `MAX_FILE_SIZE` constant)

## Storage Implementation

The controller uses Laravel's `Storage::disk('local')` facade for all file operations. This allows easy migration to S3 or other storage drivers in the future.

### Key Methods

```php
// List backups
Storage::disk('local')->files(self::BACKUP_PATH);

// Get file size
Storage::disk('local')->size($file);

// Get last modified
Storage::disk('local')->lastModified($file);

// Read file content
Storage::disk('local')->get($file);

// Get all files recursively
Storage::disk('local')->allFiles(self::PUBLIC_PATH);

// Save file
Storage::disk('local')->put($path, $content);

// Delete file
Storage::disk('local')->delete($path);

// Download file
Storage::disk('local')->download($path);

// Get absolute path
Storage::disk('local')->path($path);
```

### To Migrate to S3

Simply change `'local'` to `'s3'` in all Storage::disk() calls:

```php
// Before
Storage::disk('local')->files(self::BACKUP_PATH);

// After (when using S3)
Storage::disk('s3')->files(self::BACKUP_PATH);
```

## File Structure

```
storage/
├── app/
│   ├── backup/files/          # Backup ZIP files stored here
│   │   ├── 2026-03-10_1200.zip
│   │   └── 2026-03-10_1300.zip
│   └── public/               # User uploaded files (backed up)
│       ├── files/
│       └── images/
```

## Controller Methods

| Method | Description |
|--------|-------------|
| `index()` | List all backups (returns array with filename, size, modified) |
| `store()` | Create new backup ZIP with manifest |
| `destroy()` | Delete backup |
| `download()` | Download backup ZIP |
| `restore()` | Validate and restore files from backup |
| `upload()` | Upload and validate backup ZIP |

### Private Methods

| Method | Description |
|--------|-------------|
| `extractToPublic()` | Extract ZIP to public directory with Zip Slip protection |
| `validateBackupChecksum()` | Validate each file's checksum against manifest |

## View Integration

The view receives an array of backups:

```php
$backups = [
    [
        'filename' => '2026-03-10_1200.zip',
        'size' => 1024000,
        'modified' => 1678444800,
    ],
    // ...
];
```

Usage in Blade template:

```blade
@foreach($backups as $backup)
    {{ $backup['filename'] }}
    {{ format_size_units($backup['size']) }}
    {{ date('Y-m-d H:i:s', $backup['modified']) }}
@endforeach
```

## Performance

- SHA256 processing: ~100-500 MB/s
- For 1000 files × 1MB: ~2-10 seconds validation time

This is acceptable for backup operations which happen infrequently.

## Dependencies

- `ZipArchive` class for ZIP handling
- Laravel's `Storage` facade
- `hash()` for SHA256 checksums

## Menu Integration

The file backup feature is accessible from the Settings menu. Add to `resources/views/layouts/settings.blade.php`:

```blade
@can('manage_file_backup')
    <li class="nav-item">
        {!! link_to_route('file_backups.index', __('file_backup.list'), [], ['class' => 'nav-link'.(Request::segment(1) == 'file_backups' ? ' active' : '')]) !!}
    </li>
@endcan
```

## Language Files

Translation files are located in:
- `resources/lang/en/file_backup.php`
- `resources/lang/id/file_backup.php`
