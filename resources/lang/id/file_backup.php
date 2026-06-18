<?php

return [
    // Labels
    'index_title' => 'Daftar File Backup',
    'list' => 'Backup File',
    'file_name' => 'Nama File',
    'file_size' => 'Ukuran File',
    'created_at' => 'Dibuat pada',
    'actions' => 'Pilihan',
    'empty' => 'Belum ada file backup.',

    // Create backup file
    'create' => 'Buat Backup Baru',
    'created' => 'Backup file :filename berhasil dibuat.',
    'not_created' => 'Backup file dengan nama :filename sudah ada.',

    // Hapus backup file
    'delete' => 'Hapus',
    'delete_title' => 'Hapus file backup ini',
    'sure_to_delete_file' => 'Anda yakin akan menghapus file backup <strong>":filename"</strong>?',
    'cancel_delete' => 'Batal Hapus',
    'confirm_delete' => 'Ya, silakan hapus!',
    'delete_confirm' => 'Klik OK untuk menghapus.',
    'deleted' => 'Backup file :filename berhasil dihapus!',

    // Download backup file
    'download' => 'Download',

    // Kembalikan backup
    'restore' => 'Restore',
    'restore_title' => 'Restore file dari backup',
    'sure_to_restore' => 'Anda yakin akan mengembalikan file dengan file backup "<strong>:filename</strong>"? <br><br>Ini akan <strong>menimpa</strong> file yang ada di folder public.',
    'cancel_restore' => 'Batal Restore',
    'confirm_restore' => 'Ya, Restore File!',
    'restore_confirm' => 'Klik OK untuk me-Restore.',
    'restored' => 'File berhasil di-restore dari backup :filename',
    'restore_failed' => 'Gagal me-restore file backup :filename',
    'restore_failed_invalid' => 'File backup tidak valid. Tidak dapat me-restore.',
    'restore_failed_traversal' => 'Restore gagal: jalur file mencurigakan terdeteksi.',

    // Upload backup file
    'upload' => 'Upload File Backup',
    'uploaded' => 'Backup file :filename berhasil diupload.',
    'upload_invalid' => 'File backup tidak valid. Manifest tidak ditemukan atau rusak.',
    'upload_not_found' => 'File backup tidak ditemukan di sistem. Hanya backup yang dibuat oleh sistem yang dapat diupload.',
];
