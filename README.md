# Buku Masjid

Buku Masjid adalah sistem pengelolaan finansial dan jadwal pengajian masjid berbasis web yang dibuat dengan framework Laravel.

## Tujuan

- Meningkatkan transparansi laporan keuangan masjid/mushalla.
- Memungkinkan akses online bagi jamaah dan masyarakat umum untuk melihat laporan kas.
- Mempermudah bendahara masjid/mushalla dalam mencatat transaksi keuangan.
- Otomatisasi pembuatan laporan kas setiap kali ada transaksi.
- Mempermudah pengurus masjid/mushalla dalam mengelola jadwal khatib dan pengajian.

## Manfaat

- Meningkatkan kepercayaan jamaah/masyarakat terhadap pengelolaan dana infak masjid/mushalla.
- Memudahkan masyarakat dalam memutuskan untuk berinfak ke masjid tertentu.
- Mengurangi beban tugas bendahara dalam pembuatan laporan kas masjid/mushalla.
- Memungkinkan masyarakat/jamaah untuk memantau jadwal pengajian secara online.

## Sponsor / Mitra

Kami ingin berterima kasih kepada sponsor yang mendukung development Buku Masjid.

1. [Pondok Teknologi](https://pondokteknologi.com)
1. [Pondok IT](https://pondokit.com)
1. [Mushaira](https://mushaira.id)
1. [LKSA Al Ma'un Center](https://lynk.id/almauncenter)
1. [STIMI Banjarmasin](https://stimi-bjm.ac.id)
1. [Jetorbit](https://www.jetorbit.com)

Jika anda tertarik untuk menjadi sponsor/mitra, silakan hubungi Whatsapp Tim Buku Masjid pada halaman [Kontak Buku Masjid](https://bukumasjid.com/contact).

## Fitur

1. Pengelolaan buku catatan: Setiap kegiatan dapat dicatat di buku catatan kas yang terpisah.
2. Pengelolaan kategori/kelompok pemasukan dan pengeluaran untuk setiap buku catatan.
3. Input pemasukan dan pengeluaran.
4. Laporan:
   - Laporan kas Bulanan
   - Laporan kas per Kategori
   - Laporan kas Mingguan
5. Pengelolaan jadwal khatib Jumat.
6. Pengelolaan jadwal pengajian rutin.

## Cara Install

Aplikasi ini dapat diinstal pada server lokal maupun online dengan spesifikasi berikut:

### Kebutuhan Server

1. PHP 8.1 (dan sesuai dengan [persyaratan server Laravel 10.x](https://laravel.com/docs/10.x/deployment#server-requirements)).
2. Database MySQL atau MariaDB.
3. SQLite (digunakan untuk pengujian otomatis).

### Langkah Instalasi

1. Clone repositori ini dengan perintah: `git clone https://github.com/buku-masjid/buku-masjid.git`
2. Masuk ke direktori buku-masjid: `$ cd buku-masjid`
3. Instal dependensi menggunakan: `$ composer install`
4. Salin berkas `.env.example` ke `.env`: `$ cp .env.example .env`
5. Generate kunci aplikasi: `$ php artisan key:generate`
6. Buat database MySQL untuk aplikasi ini.
7. Konfigurasi database dan pengaturan lainnya di berkas `.env`.
    ```
    APP_URL=http://localhost
    APP_TIMEZONE="Asia/Makassar"

    DB_DATABASE=homestead
    DB_USERNAME=homestead
    DB_PASSWORD=secret

    MASJID_NAME="Masjid Ar-Rahman"
    MASJID_DEFAULT_BOOK_ID=1
    AUTH_DEFAULT_PASSWORD=password

    MONEY_CURRENCY_CODE="Rp"
    MONEY_PRECISION=2
    MONEY_DECIMAL_SEPARATOR=","
    MONEY_THOUSANDS_SEPARATOR="."
    ```
8. Jalankan migrasi database: `$ php artisan migrate --seed`
9. Buat kunci passport: `$ php artisan passport:keys`
10. Buat tautan penyimpanan: `$ php artisan storage:link`
11. Mulai server: `$ php artisan serve`
12. Buka web browser dengan alamat web: http://localhost:8000, kemudian masuk dengan akun bawaan:
    ```
    email: admin@example.net
    password: password
    ```

### Langkah Install dengan Docker

Untuk menggunakan docker silahkan jalankan perintah ini di terminal:

1. Buat file .env
    ```bash
    $ cp .env.example .env
    ```
2. Update untuk mengubah env `DB_HOST`:
    ```bash
    DB_HOST=mysql_host
    ```
    Atau Anda dapat mengotomatiskan proses ini menggunakan perintah ini.
    ```bash
    COPY .env.example .env.tmp
    sed 's/DB_HOST=127.0.0.1/DB_HOST=mysql_host/' .env.tmp > .env && rm .env.tmp
    ```
3. Build docker images dan jalankan container:
    ```bash
    docker-compose build
    docker-compose up -d
    ```
4. Jalankan database migration:
    ```bash
    docker-compose exec server php artisan migrate --seed
    ```
5. Buka web browser dengan alamat web: http://localhost:8000, kemudian login dengan default user:
    ```
    email: admin@example.net
    password: password
    ```
6. Untuk masuk ke docker container shell:
    ```bash
    docker-compose exec server sh
    docker-compose exec mysql bash
    ```

### Data Demo

Ketika sudah ter-install di localhost, kita bisa generate data dummy untuk simulasi sistem buku masjid. Datad demo dapat di-generate dengan perintah berikut:

Generate demo data (3 bulan terakhir):

```bash
$ php artisan buku-masjid:generate-demo-data
```

Hapus semua demo data (yang `created_at` nya `NULL`)

```bash
$ php artisan buku-masjid:remove-demo-data
```

Lengkapnya dapat dilihat pada: [Dokumentasi buku-masjid/demo-data](https://github.com/buku-masjid/demo-data#cara-pakai).

## Screenshot

#### Laporan Bulanan

![Laporan Bulanan](public/screenshots/01-monthly-report-for-public.jpg)

#### Laporan Per Kategori

![Laporan Per](public/screenshots/02-categorized-report-for-public.jpg)

#### Laporan Per Pekan

![Laporan Per](public/screenshots/03-weekly-report-for-public.jpg)

#### Jadwal Pengajian

![Jadwal Pengajian](public/screenshots/04-lecturing-schedule-for-this-week.jpg)

## Kontribusi

Jika Anda ingin berkontribusi pada proyek ini, kami sangat menghargainya. Berikut beberapa yang dapat Anda lakukan:

1. Laporkan [issue](https://github.com/buku-masjid/buku-masjid/issues) jika Anda menemui kesalahan atau bug.
2. Sampaikan [diskusi](https://github.com/buku-masjid/buku-masjid/discussions) jika Anda ingin mengusulkan fitur baru atau perubahan pada fitur yang sudah ada.
3. Ajukan [pull request](https://github.com/buku-masjid/buku-masjid/pulls) untuk perbaikan bug, penambahan fitur baru, atau perbaikan label.

## Kontak

Untuk Diskusi:

* [Grup Chat Telegram](https://t.me/bukumasjid_id)
* [Usulan Fitur Baru](https://github.com/buku-masjid/buku-masjid/discussions)

Untuk pengumuman dan update:

* [Follow Twitter](https://twitter.com/bukumasjid)
* [Like Facebook Page](https://facebook.com/bukumasjid)
* [Telegram Channel](https://t.me/bukumasjid)

## Lisensi

Proyek Buku Masjid merupakan perangkat lunak open-source yang dilisensikan di bawah [Lisensi MIT](LICENSE).

### Credits

Proyek ini menggunakan bunyi bip dari [Pixabay](https://pixabay.com/sound-effects/race-start-beeps-125125) oleh [transcendedlifting](https://pixabay.com/users/transcendedlifting-30596364) (Pixabay License)
