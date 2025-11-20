<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

-   [Simple, fast routing engine](https://laravel.com/docs/routing).
-   [Powerful dependency injection container](https://laravel.com/docs/container).
-   Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
-   Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
-   Database agnostic [schema migrations](https://laravel.com/docs/migrations).
-   [Robust background job processing](https://laravel.com/docs/queues).
-   [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

-   **[Vehikl](https://vehikl.com)**
-   **[Tighten Co.](https://tighten.co)**
-   **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
-   **[64 Robots](https://64robots.com)**
-   **[Curotec](https://www.curotec.com/services/technologies/laravel)**
-   **[DevSquad](https://devsquad.com/hire-laravel-developers)**
-   **[Redberry](https://redberry.international/laravel-development)**
-   **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Sistem Pemilihan Digital (Pemira PMK 2025)

### Alur Singkat di Lapangan

1. Panitia menyiapkan 5-6 laptop yang sudah membuka halaman aplikasi (domain yang sama).
2. Setiap pemilih menerima NIM + Token unik dari panitia.
3. Pemilih login (NIM + Token) → mendapatkan Bearer Token (Sanctum plain text) disimpan di memori browser.
4. Pemilih melihat daftar kandidat.
5. Pemilih memilih salah satu kandidat (vote hanya sekali) → sistem merekam ke tabel `suara` dan menandai `pemilih.sudah_memilih = true`.
6. Admin / Super Admin dapat memantau rekap suara realtime.

### Peran

-   Super Admin: manage keseluruhan, melihat hasil, (dapat dikembangkan untuk CRUD kandidat/pemilih).
-   Admin: melihat hasil (dapat dibatasi sesuai kebutuhan).
-   Pemilih: login dan melakukan satu kali voting.

### Struktur Tabel Inti

-   `admin` : username, password (hashed), role (`admin` / `super-admin`).
-   `kandidat` : data kandidat termasuk visi misi.
-   `pemilih` : data pemilih + token login + status memilih.
-   `suara` : catatan satu pemilih satu suara, menyimpan denormalisasi beberapa field pemilih serta `kandidat_id`.

### Instalasi (Local Dev)

```
git clone <repo>
cd pemira-pmk-2025-BE-
composer install
cp .env.example .env
php artisan key:generate
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
php artisan db:seed
```

Pastikan di `.env` sudah diatur koneksi database. Tidak membutuhkan konfigurasi khusus untuk Sanctum karena kita menggunakan token personal (bukan SPA cookie mode).

### Auth API

Setelah install dependency Sanctum:

```
composer install
php artisan migrate
php artisan db:seed
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

### Endpoint

| Method | URL                         | Body                                                  | Deskripsi                       |
| ------ | --------------------------- | ----------------------------------------------------- | ------------------------------- |
| POST   | /api/auth/admin/login       | {"username":"admin","password":"Admin123!"}           | Login admin biasa               |
| POST   | /api/auth/super-admin/login | {"username":"superadmin","password":"SuperAdmin123!"} | Login super admin               |
| POST   | /api/auth/pemilih/login     | {"nim":"123456789","token":"abc123"}                  | Login pemilih menggunakan token |
| POST   | /api/auth/logout            | (Authorization: Bearer <token>)                       | Logout & revoke token           |

### Endpoint Voting & Data Kandidat

Semua endpoint di bawah perlu header `Authorization: Bearer <token>`.

| Method | URL                  | Body              | Deskripsi                                          |
| ------ | -------------------- | ----------------- | -------------------------------------------------- |
| GET    | /api/kandidat        | -                 | Daftar semua kandidat (pemilih & admin)            |
| GET    | /api/vote/status     | -                 | Status pemilih: sudah_memilih + pilihan            |
| POST   | /api/vote            | {"kandidat_id":1} | Melakukan voting (hanya 1x)                        |
| GET    | /api/results/summary | -                 | Rekap total suara per kandidat (admin/super-admin) |

Semua response sukses:

```json
{
	"status": "success",
	"role": "admin|super-admin|pemilih",
	"token": "<plain_text_token>",
	"admin": {"id":1,"username":"admin","role":"admin"} // atau
	"pemilih": {"id":10,"nim":"...","nama":"..."}
}
```

Response error (contoh):

```json
{
    "message": "The given data was invalid.",
    "errors": { "username": ["Kredensial admin tidak valid"] }
}
```

Header Authorization untuk endpoint yang butuh autentikasi:

````
Authorization: Bearer <token>

### Contoh Flow Voting (Front-end Sederhana)
1. POST /api/auth/pemilih/login → simpan `token` di memory (state) bukan localStorage jika paranoid.
2. GET /api/kandidat → tampilkan list.
3. POST /api/vote {kandidat_id} → disabled tombol setelah sukses.
4. GET /api/vote/status (opsional refresh) → konfirmasi pilihan.

### Validasi & Aturan
- Pemilih tanpa token valid tidak bisa login.
- Pemilih hanya bisa vote sekali: dicek `sudah_memilih` & eksistensi di tabel `suara` dalam transaksi.
- Admin/Super Admin dibedakan melalui kolom `role` dan diberi ability berbeda pada token.
- Rekap suara menghitung jumlah rows `suara` per `kandidat_id` dan menampilkan total pemilih sudah memilih vs total pemilih.

### Deployment di Jagoan Hosting (Shared Hosting / cPanel)

Langkah umum (diasumsikan paket hosting mendukung PHP 8.2+):

1. Buat Subdomain / Domain: misal `pemira.domainmu.com` lewat cPanel.
2. Atur Document Root agar menunjuk ke folder `public/` laravel.
	- Jika tidak bisa ubah doc root: upload isi folder `public` ke root, dan pindahkan seluruh project ke subfolder lalu sesuaikan `index.php` require path.
3. Upload Source:
	- Opsi A (SSH): `git clone` langsung di server.
	- Opsi B (Manual): Jalankan `composer install` dan `npm run build` di lokal, kompres folder (kecuali `node_modules` jika sudah build), upload via cPanel File Manager atau SFTP.
4. Jalankan Composer di Server (jika tersedia):
	- `composer install --no-dev --optimize-autoloader`
5. Buat Database MySQL melalui cPanel → catat host, nama db, user, password.
6. Edit `.env`:
	```
	APP_ENV=production
	APP_DEBUG=false
	APP_URL=https://pemira.domainmu.com
	DB_CONNECTION=mysql
	DB_HOST=<host>
	DB_PORT=3306
	DB_DATABASE=<nama_db>
	DB_USERNAME=<user>
	DB_PASSWORD=<pass>
	SESSION_DRIVER=file
	CACHE_DRIVER=file
	QUEUE_CONNECTION=sync
	SANCTUM_STATEFUL_DOMAINS=pemira.domainmu.com
	```
7. Jalankan migrasi & seed (via SSH atau laravel task scheduler):
	```
	php artisan key:generate
	php artisan migrate --force
	php artisan db:seed --force
	```
8. Optimisasi produksi:
	```
	php artisan config:cache
	php artisan route:cache
	php artisan view:cache
	```
9. Pastikan permission:
	- Folder `storage/` dan `bootstrap/cache` writable (755 atau 775).
10. Tes endpoint:
	- `curl -X POST https://pemira.domainmu.com/api/auth/pemilih/login -d '{"nim":"...","token":"..."}' -H 'Content-Type: application/json'`

### Rotasi Token Pemilih
Jika perlu regenerasi token: update kolom `token` di tabel `pemilih` dengan nilai unik baru (bisa script serupa `GenerateTokenSeeder`). Minta pemilih ambil token baru di meja panitia.

### Keamanan Tambahan
- Gunakan HTTPS (aktifkan SSL di Jagoan Hosting / AutoSSL).
- Nonaktifkan `APP_DEBUG` di produksi.
- Batasi ukuran response kandidat (hindari data sensitif).
- Optional: tambahkan rate limiting di route login (`ThrottleMiddleware`).

### Pengembangan Lanjutan
- Endpoint CRUD kandidat (admin/super-admin).
- Endpoint export hasil (CSV / PDF).
- Audit log voting (waktu, IP laptop).
- Realtime dashboard menggunakan broadcasting (opsional).

### Perintah Cepat (Ringkas)
````

composer install
php artisan migrate --force
php artisan db:seed --force
php artisan config:cache && php artisan route:cache

```

Selesai. Aplikasi siap digunakan pada sesi pemilihan.
```
