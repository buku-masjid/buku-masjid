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

Each backup ZIP contains a `manifest.json` file with checksums for all files:

```json
{
    "created_at": "2026-03-10 12:00:00",
    "files": {
        "files/2026/03/image1.jpg": "abc123...",
        "files/2026/03/document.pdf": "def456..."
    }
}
```

### 2. File Checksum Validation

When validating a backup:

1. Extract each file from ZIP
2. Calculate SHA256 checksum for each file
3. Compare with checksums stored in manifest

This validates file contents, not ZIP structure, making it reliable.

### 3. Upload Security

When uploading a backup:

1. Validate manifest.json exists
2. Validate each file's checksum matches manifest
3. If valid, accept the upload

This ensures:

- Only backups created by the system can be uploaded
- Modified or corrupted backups are rejected

### 4. Restore Security

When restoring a backup:

1. Validate file checksums against manifest
2. Apply Zip Slip protection (path traversal prevention)
3. Extract files to public directory

### 5. Zip Slip Protection

The `safeExtract()` method prevents path traversal attacks by:

- Getting the real path of the target directory
- For each entry in ZIP, verifying the resolved path stays within the target directory
- Rejecting any entries that would escape the target directory

### 6. Size Limit

Maximum upload size: 50MB (configurable via `MAX_FILE_SIZE` constant)

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
| `index()` | List all backups |
| `store()` | Create new backup ZIP |
| `destroy()` | Delete backup |
| `download()` | Download backup ZIP |
| `restore()` | Restore files from backup |
| `upload()` | Upload backup ZIP |

## Performance

- SHA256 processing: ~100-500 MB/s
- For 1000 files × 1MB: ~2-10 seconds validation time

This is acceptable for backup operations which happen infrequently.

## Dependencies

- `ZipArchive` class for ZIP handling
- Laravel's `File` and `Storage` facades
- `hash_file()` for SHA256 checksums
