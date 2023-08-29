# Buku Masjid

Buku Masjid adalah sistem pengelolaan keuangan dan jadwal pengajian masjid berbasis web yang dibuat dengan framework Laravel.

## Tujuan
  - Transparansi laporan keuangan masjid/mushalla.
  - Laporan kas dapat diakses secara online melalui web oleh jamaah dan masyarakat umum.
  - Mempermudah bendahara masjid/mushalla untuk input data transaksi keuangan.
  - Laporan kas otomatis setiap input transaksi.
  - Mempermudah pengurus masjid/mushalla untuk mengelola jadwal khatib dan pengajian

## Manfaat
  - Meningkatkan kepercayaan jamaah/masyarakat perihal pengelolaan dana infaq masjid/mushalla.
  - Mempermudah masyarakat untuk memutuskan berinfaq ke masjid mana.
  - Meringankan tugas bendahara dalam membuat laporan kas masjid/mushalla
  - Masyarakat/jamaah dapat memantau jadwal pengajian di masjid tersebut secara online.

## Fitur

1. Pengelolaan buku catatan, setiap kegiatan dapat dicatat di buku catatan kas yang terpisah.
2. Pengelolaan kategori/kelompok pemasukan dan pengeluaran untuk setiap buku catatan.
3. Input pemasukan dan pengeluaran.
4. Laporan:
    - Laporan kas Bulanan
    - Laporan kas per Kategori
    - Laporan kas Mingguan
5. Pengelolaan jadwal khatib jumat
5. Pengelolaan jadwal pengajian rutin

## Cara Install

Aplikasi ini dapat dipasang pada server lokal dan online dengan spesifikasi berikut::

### Kebutuhan Server

1. PHP 8.1 (dan mengikuti [server requirement Laravel 10.x](https://laravel.com/docs/10.x/deployment#server-requirements)),
2. Database MySQL atau MariaDB,
3. SQlite (untuk automated testing).

### Langkah Instalasi

1. Clone the repo : `git clone https://github.com/buku-masjid/buku-masjid.git`
2. `$ cd buku-masjid`
3. `$ composer install`
4. `$ cp .env.example .env`
5. `$ php artisan key:generate`
6. Buat **database pada mysql** untuk aplikasi ini  
7. **Setting database** dan config lainnya pada file `.env`
    ```
    APP_URL=http://localhost
    APP_TIMEZONE="Asia/Makassar"

    DB_DATABASE=homestead
    DB_USERNAME=homestead
    DB_PASSWORD=secret

    MASJID_NAME="Masjid Ar-Rahman"
    MASJID_DEFAULT_BOOK_ID=1
    AUTH_DEFAULT_PASSWORD=password
    ```
8. `$ php artisan migrate --seed`
8. `$ php artisan storage:link`
9. `$ php artisan serve`
10. Login dengan default user:
    ```
    email: admin@example.net
    password: password
    ```

## Kontribusi

Jika anda ingin berkontribusi pada proyek ini, kami sangat berterima kasih. Beberapa yang dapat dilakukan:

1. Submit [issue](https://github.com/buku-masjid/buku-masjid/issues) jika anda menemukan error atau bug.
2. Submit [discussion](https://github.com/buku-masjid/buku-masjid/discussions) jika anda ingin mengusulkan fitur baru atau mengubah fitur yang sudah ada.
3. Submit [pull request](https://github.com/buku-masjid/buku-masjid/pulls) untuk perbaikan bug, membuat fitur baru, atau kesalahan penulisan pada label.

## Lisensi

Proyek Buku Masjid merupakan software open-source di bawah lisensi [Lisensi MIT](LICENSE).
