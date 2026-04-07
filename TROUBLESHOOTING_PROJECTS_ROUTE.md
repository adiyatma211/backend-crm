# Panduan Pemecahan Masalah Route Projects "Not Found"

## Deskripsi Masalah

Pengguna mendapatkan pesan error "Not Found - The requested resource /projects was not found on this server" meskipun sudah login.

## Analisis Masalah

Berdasarkan investigasi, berikut adalah temuan:

1. ✅ Route sudah terdaftar dengan benar di `routes/web.php`
2. ✅ Route terlihat di daftar route: `GET|HEAD projects` → `projects.index`
3. ✅ Controller `ProjectController@index` sudah ada dan valid
4. ⚠️ Route terlindungi oleh middleware `auth` (membutuhkan login)
5. ⚠️ Tidak ada cache route yang terdeteksi

## Kemungkinan Penyebab

### 1. Masalah Autentikasi
- Session mungkin tidak valid atau expired
- Cookie tidak tersimpan dengan benar
- User tidak benar-benar login

### 2. Masalah Cache
- Route cache mungkin lama (jarang terjadi karena tidak ada file cache)
- Config cache mungkin perlu dibersihkan

### 3. Masalah URL
- URL yang diakses mungkin salah
- Ada redirect yang tidak sesuai

## Solusi Langkah demi Langkah

### Langkah 1: Bersihkan Semua Cache

```bash
# Bersihkan route cache
php artisan route:clear

# Bersihkan config cache
php artisan config:clear

# Bersihkan application cache
php artisan cache:clear

# Bersihkan view cache
php artisan view:clear
```

**Output yang diharapkan:**
```
Route cache cleared!
Configuration cache cleared!
Application cache cleared!
Compiled views cleared!
```

### Langkah 2: Verifikasi Route Terdaftar

```bash
# Tampilkan semua route yang mengandung kata "projects"
php artisan route:list --path=projects
```

**Output yang diharapkan:**
```
  GET|HEAD  projects ......................... projects.index › ProjectController@index
  POST      projects .......................... projects.store › ProjectController@store
  GET|HEAD  projects/create ................... projects.create › ProjectController@create
  GET|HEAD  projects/{project} ................ projects.show › ProjectController@show
  PUT       projects/{project} ................ projects.update › ProjectController@update
  DELETE    projects/{project} ................ projects.destroy › ProjectController@destroy
  GET|HEAD  projects/{project}/edit ........... projects.edit › ProjectController@edit
```

### Langkah 3: Cek Route dengan Auth Middleware

```bash
# Tampilkan route yang menggunakan middleware auth
php artisan route:list --middleware=auth | grep projects
```

**Output yang diharapkan:**
```
GET|HEAD  projects ......................... projects.index › ProjectController@index
```

### Langkah 4: Periksa URL yang Benar

URL yang benar untuk mengakses halaman projects:
- **Base URL:** `http://localhost:8000/projects`
- **Route Name:** `projects.index`
- **Middleware:** `auth` (harus login terlebih dahulu)

### Langkah 5: Tes Autentikasi

```bash
# Masuk ke tinker untuk cek status auth
php artisan tinker
```

Lalu jalankan perintah ini di dalam tinker:
```php
// Cek apakah ada user yang login
auth()->check();

// Jika ingin cek user yang login
auth()->user();
```

**Output yang diharapkan (jika sudah login):**
```
true  // untuk auth()->check()
```

### Langkah 6: Cek Session dan Database

Pastikan database sudah terhubung dan table `sessions` ada:

```bash
# Cek koneksi database
php artisan tinker --execute="dd(DB::connection()->getPdo());"
```

Output harus menunjukkan koneksi berhasil.

### Langkah 7: Hapus Semua Session (Opsional)

Jika ingin memulai session baru:

```bash
# Hapus semua session di database
php artisan tinker --execute="DB::table('sessions')->truncate();"
```

### Langkah 8: Restart Server

```bash
# Stop server (Ctrl+C) lalu jalankan lagi
php artisan serve
```

## Cara Mengakses Route dengan Benar

### 1. Pastikan Sudah Login
- Buka `http://localhost:8000/login`
- Login dengan kredensial yang valid
- Pastikan berhasil masuk ke dashboard

### 2. Akses Route Projects
Setelah login, akses salah satu dari:
- URL langsung: `http://localhost:8000/projects`
- Dari sidebar dashboard: klik menu "Projects" → "All Projects"

### 3. Dalam Kode
Jika ingin redirect ke route ini dari controller:
```php
return redirect()->route('projects.index');
```

Jika ingin generate URL:
```php
$url = route('projects.index');
// atau
$url = url('/projects');
```

## Masalah Umum dan Solusi

### Masalah 1: "Route not found" tapi user sudah login

**Penyebab:** Session expired atau cookie tidak tersimpan

**Solusi:**
```bash
# Bersihkan cache dan restart server
php artisan cache:clear
php artisan session:table
php artisan migrate
php artisan serve
```

Login ulang setelah server restart.

### Masalah 2: Redirect loop

**Penyebab:** Middleware mengalami konflik

**Solusi:**
Cek file `routes/web.php` baris 16:
```php
Route::middleware(['auth'])->group(function () {
    // semua route di dalam ini membutuhkan auth
});
```

Pastikan tidak ada route yang saling redirect satu sama lain.

### Masalah 3: Route cache tidak terupdate

**Penyebab:** Cache lama masih tersimpan

**Solusi:**
```bash
php artisan route:clear
php artisan route:cache  # untuk membuat cache baru (opsional untuk production)
```

### Maskah 4: URL salah slash

**Penyebab:** Mengakses `/projects/` dengan trailing slash

**Solusi:**
Gunakan URL tanpa trailing slash:
- ✅ `http://localhost:8000/projects`
- ❌ `http://localhost:8000/projects/`

Catatan: Laravel seharusnya bisa menangani keduanya, tapi lebih aman tanpa trailing slash.

## Verifikasi Route Berfungsi

### Test Manual di Browser

1. Buka browser dan akses `http://localhost:8000/login`
2. Login dengan akun yang valid
3. Setelah berhasil login, akses `http://localhost:8000/projects`
4. Seharusnya muncul halaman daftar projects

### Test dengan Laravel Telescope (jika terinstall)

```bash
php artisan telescope:install
php artisan migrate
php artisan serve
```

Buka `http://localhost:8000/telescope` untuk melihat request log.

### Cek Log Laravel

```bash
# Lihat log terbaru
tail -f storage/logs/laravel.log
```

Akses route `/projects` dan lihat error yang muncul di log.

## Checklist Pemecahan Masalah

- [ ] Bersihkan route cache
- [ ] Bersihkan config cache
- [ ] Bersihkan application cache
- [ ] Verifikasi route terdaftar
- [ ] Pastikan user sudah login
- [ ] Cek URL yang diakses
- [ ] Restart server
- [ ] Cek log Laravel
- [ ] Hapus session dan login ulang
- [ ] Cek koneksi database

## Kontak untuk Dukungan Tambahan

Jika masalah masih berlanjut:

1. Cek dokumentasi Laravel: https://laravel.com/docs/routing
2. Cek error detail di `storage/logs/laravel.log`
3. Aktifkan debug mode di `.env`: `APP_DEBUG=true`
4. Share output dari `php artisan route:list` untuk analisis lebih lanjut

## Ringkasan

Route `/projects` sudah terdaftar dengan benar dan berfungsi. Masalah yang dialami kemungkinan besar karena:
1. Session tidak valid → login ulang
2. Cache lama → bersihkan cache
3. URL salah → gunakan `http://localhost:8000/projects`

Ikuti langkah-langkah di atas secara berurutan untuk memecahkan masalah.