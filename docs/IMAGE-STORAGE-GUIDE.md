# Panduan Penyimpanan Gambar

## Ringkasan

Aplikasi ini menyimpan gambar secara langsung di folder `public/` sebagai pengganti penyimpanan menggunakan Laravel Storage. Pendekatan ini memudahkan deployment pada shared hosting tanpa perlu menjalankan perintah `php artisan storage:link`.

**Mengapa pendekatan ini?**
- Tidak memerlukan symbolic link (`storage:link`)
- Dapat langsung diakses melalui browser tanpa konfigurasi tambahan
- Lebih mudah untuk deployment pada shared hosting
- Dapat mengambil data dari database lama yang menggunakan path `public/`

## Struktur Direktori

```
public/
├── projects/        # Gambar untuk portfolio/projects
│   └── project_1234567890_abc123.jpg
└── logos/           # Logo situs
    ├── logo_1234567890.png
    ├── logo_light_1234567890.png
    └── logo_dark_1234567890.png
```

### Path yang Disimpan di Database

- **Projects**: `projects/nama_file.jpg`
- **Logos**: `logos/nama_file.png`

Path yang disimpan di database adalah **path relatif dari folder public**, sehingga dapat digunakan dengan helper `asset()` untuk menghasilkan URL lengkap.

## Perubahan yang Dibuat

### 1. ProjectController (app/Http/Controllers/ProjectController.php)

**Store - Baru (lines 48-54)**
```php
$imagePath = null;
if ($request->hasFile('image')) {
    $file = $request->file('image');
    $filename = 'project_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
    $file->move(public_path('projects'), $filename);
    $imagePath = 'projects/' . $filename;
}
```

**Update - Menghapus file lama (lines 115-123)**
```php
if ($request->hasFile('image')) {
    if ($project->image && file_exists(public_path($project->image))) {
        unlink(public_path($project->image));
    }
    
    $file = $request->file('image');
    $filename = 'project_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
    $file->move(public_path('projects'), $filename);
    $updateData['image'] = 'projects/' . $filename;
}
```

### 2. SettingsController (app/Http/Controllers/SettingsController.php)

**Store - Upload logo (lines 46-57)**
```php
if ($request->hasFile('logo')) {
    if ($settings->logo_url && file_exists(public_path($settings->logo_url))) {
        unlink(public_path($settings->logo_url));
    }
    
    $file = $request->file('logo');
    $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
    $file->move(public_path('logos'), $filename);
    $settingsData['logo_url'] = 'logos/' . $filename;
    $settingsData['logo_light_url'] = 'logos/' . $filename;
    $settingsData['logo_dark_url'] = 'logos/' . $filename;
}
```

**uploadLogo - Upload logo tertentu (lines 87-146)**
- Mendukung tipe logo: `general`, `light`, `dark`
- Menghapus logo lama sebelum menyimpan yang baru
- Mengembalikan JSON dengan URL lengkap untuk API

### 3. API Controllers

**ProjectController - Menambahkan image_url (lines 25-28)**
```php
$projects->transform(function ($project) {
    $project->image_url = asset($project->image);
    return $project;
});
```

**SettingsController - Menambahkan URL lengkap (lines 16-20)**
```php
$settings->logo_url_full = $settings->logo_url ? asset($settings->logo_url) : null;
$settings->logo_light_url_full = $settings->logo_light_url ? asset($settings->logo_light_url) : null;
$settings->logo_dark_url_full = $settings->logo_dark_url ? asset($settings->logo_dark_url) : null;
```

### 4. Views

Di Blade templates, gunakan helper `asset()` untuk menampilkan gambar:

```php
<img src="{{ asset($project->image) }}" alt="{{ $project->title }}">
<img src="{{ asset($settings->logo_url) }}" alt="{{ $settings->site_name }}">
```

## Penghapusan File Otomatis

Sistem secara otomatis menghapus file lama ketika:

### Update Project
- File gambar lama dihapus (line 116-118 di ProjectController)
- File baru disimpan dengan nama unik

### Update Logo
- Logo lama dihapus berdasarkan tipe (general, light, dark)
- File baru disimpan dengan timestamp untuk memastikan keunikan

### Delete Logo
- `resetLogo()` menghapus semua logo (lines 152-160)
- `deleteLogo()` menghapus logo berdasarkan tipe (lines 171-209)

## Format Respons API

### Projects API

**Contoh respons GET /api/projects**

```json
{
  "success": true,
  "message": "Projects retrieved successfully",
  "data": [
    {
      "id": 1,
      "title": "Portfolio Website",
      "slug": "portfolio-website",
      "category": "Web Development",
      "description": "Modern portfolio website",
      "status": "Production",
      "image": "projects/project_1712345678_abc123.jpg",
      "image_url": "http://example.com/projects/project_1712345678_abc123.jpg",
      "project_url": "https://example.com",
      "is_active": true,
      "display_order": 1
    }
  ],
  "count": 1
}
```

### Settings API

**Contoh respons GET /api/settings**

```json
{
  "success": true,
  "message": "Settings retrieved successfully",
  "data": {
    "id": 1,
    "site_name": "Sentosa CMS",
    "site_description": "Content Management System",
    "logo_url": "logos/logo_1712345678.png",
    "logo_url_full": "http://example.com/logos/logo_1712345678.png",
    "logo_light_url": "logos/logo_1712345678.png",
    "logo_light_url_full": "http://example.com/logos/logo_1712345678.png",
    "logo_dark_url": "logos/logo_1712345678.png",
    "logo_dark_url_full": "http://example.com/logos/logo_1712345678.png",
    "logo_alt_text": "Sentosa",
    "enable_dark_mode": false,
    "enable_compact_mode": false
  }
}
```

## Daftar Pengecekan Pengujian

### Projects

- [ ] Buat project baru dengan upload gambar
- [ ] Verifikasi gambar tersimpan di `public/projects/`
- [ ] Verifikasi path di database `projects/nama_file.jpg`
- [ ] Edit project dengan gambar baru
- [ ] Verifikasi gambar lama terhapus
- [ ] Edit project tanpa mengubah gambar
- [ ] Hapus project

### Settings

- [ ] Upload logo general
- [ ] Upload logo light
- [ ] Upload logo dark
- [ ] Verifikasi semua logo tersimpan di `public/logos/`
- [ ] Upload logo baru dan verifikasi logo lama terhapus
- [ ] Reset logo ke default (tanpa logo)
- [ ] Delete logo tertentu (general/light/dark)

### API Endpoints

- [ ] GET `/api/projects` - Cek image_url
- [ ] GET `/api/projects/{slug}` - Cek image_url
- [ ] GET `/api/settings` - Cek logo_url_full
- [ ] GET `/api/settings/logo` - Cek semua URL logo
- [ ] POST `/api/settings/update` - Update settings text
- [ ] POST `/api/settings/upload-logo` - Upload logo via API

### View Rendering

- [ ] Halaman list projects menampilkan gambar dengan `asset()`
- [ ] Halaman detail project menampilkan gambar
- [ ] Halaman settings menampilkan logo
- [ ] Frontend menampilkan logo dari API dengan URL lengkap

## Pemecahan Masalah

### Error: "Permission denied"

**Masalah**: Tidak dapat menyimpan atau menghapus file di `public/`

**Solusi**:
```bash
# Linux/Mac
chmod -R 755 public/projects
chmod -R 755 public/logos
chmod -R 777 public/projects  # Jika perlu akses tulis penuh
chmod -R 777 public/logos

# Windows (IIS)
pastikan user IIS_IUSR dan IUSR memiliki permission Write pada folder projects dan logos
```

### Error: "Directory not found"

**Masalah**: Folder `projects/` atau `logos/` belum ada

**Solusi**:
```bash
# Buat folder secara manual
mkdir public/projects
mkdir public/logos

# Atau buat lewat aplikasi (PHP akan membuatnya otomatis jika ada permission)
```

### Gambar tidak tampil di browser

**Kemungkinan penyebab**:
1. Path di database salah
2. File tidak ada di direktori yang benar
3. Permission pada folder/file salah

**Cek dengan perintah**:
```bash
# Cek apakah file ada
ls -la public/projects/
ls -la public/logos/

# Cek path di database
php artisan tinker
>>> $project = App\Models\Project::first();
>>> echo $project->image;
>>> echo asset($project->image);
```

### Upload gagal

**Kemungkinan penyebab**:
1. Ukuran file terlalu besar (max 2048 KB / 2 MB)
2. Tipe file tidak didukung (png, jpg, jpeg, webp, svg)
3. PHP upload_max_filesize terlalu kecil

**Cek konfigurasi PHP**:
```bash
php -i | grep upload_max_filesize
php -i | grep post_max_size
```

Edit `php.ini` jika perlu:
```ini
upload_max_filesize = 10M
post_max_size = 10M
```

### API mengembalikan URL localhost

**Masalah**: URL gambar menggunakan `http://localhost` padahal di production

**Solusi**:
Pastikan `APP_URL` di `.env` sudah di-set dengan domain yang benar:
```env
APP_URL=http://domain-anda.com
```

Kemudian jalankan:
```bash
php artisan config:clear
php artisan cache:clear
```

## Catatan Tambahan

### Keamanan
- Pastikan folder `public/projects/` dan `public/logos/` tidak dapat diakses langsung untuk upload (hanya melalui aplikasi)
- Validasi tipe file sudah diimplementasikan di controller (image|mimes:png,jpg,jpeg,webp,svg)
- Batas ukuran file 2MB (2048 KB) untuk mencegah abuse

### Backup
- Folder `public/projects/` dan `public/logos/` harus di-backup secara rutin karena file tidak di-commit ke git
- Pertimbangkan untuk membuat script backup otomatis ke cloud storage

### Migrasi dari Storage lama
Jika sebelumnya menggunakan Laravel Storage dengan symbolic link:
1. Pindahkan file dari `storage/app/public/` ke `public/`
2. Update path di database jika perlu
3. Hapus symbolic link `public/storage`
4. Hapus file konfigurasi storage yang tidak diperlukan
