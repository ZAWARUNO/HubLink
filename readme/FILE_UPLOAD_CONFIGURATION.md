# Konfigurasi Upload File hingga 10MB

## Masalah
Upload file produk digital terbatas hingga 2MB padahal ingin bisa upload hingga 10MB.

## ✅ Solusi yang Sudah Diterapkan

### Update Terbaru (16 Okt 2025)
Form tambah dan edit produk sudah diperbaiki dengan fitur:
- ✅ **Preview gambar** saat file dipilih
- ✅ **Informasi file** yang dipilih (nama & ukuran)
- ✅ **Validasi ukuran file** di client-side (sebelum upload)
- ✅ **Loading indicator** saat proses upload
- ✅ **Tombol hapus** untuk membatalkan pilihan file
- ✅ **Hover effect** pada area upload

## Solusi Konfigurasi Server

### 1. Laravel Validation (✅ Sudah Dikonfigurasi)
File `app/Http/Controllers/CMS/ProductController.php` sudah mengizinkan upload hingga 10MB:
- Gambar produk: max 2MB
- File produk digital: max 10MB (10240 KB)

### 2. .htaccess Configuration (✅ Sudah Diterapkan)
File `public/.htaccess` sudah diupdate dengan konfigurasi:
```apache
php_value upload_max_filesize 10M
php_value post_max_size 12M
php_value max_execution_time 300
php_value max_input_time 300
```

### 3. Konfigurasi PHP di Laragon (⚠️ PERLU DILAKUKAN MANUAL)

Jika setelah mengupdate `.htaccess` masih tidak bisa upload file besar, Anda perlu mengupdate `php.ini` di Laragon:

#### Langkah-langkah:

1. **Buka Laragon**
2. **Klik Menu → PHP → php.ini**
3. **Cari dan ubah nilai berikut:**
   ```ini
   upload_max_filesize = 10M
   post_max_size = 12M
   max_execution_time = 300
   max_input_time = 300
   memory_limit = 256M
   ```

4. **Simpan file php.ini**
5. **Restart Apache di Laragon:**
   - Klik tombol "Stop All"
   - Kemudian klik "Start All"

#### Penjelasan Setting:
- `upload_max_filesize`: Ukuran maksimal file yang bisa diupload (10MB)
- `post_max_size`: Ukuran maksimal data POST (harus lebih besar dari upload_max_filesize)
- `max_execution_time`: Waktu maksimal eksekusi script (300 detik = 5 menit)
- `max_input_time`: Waktu maksimal untuk parsing input data
- `memory_limit`: Memori maksimal yang bisa digunakan PHP

## Verifikasi

Setelah melakukan perubahan, coba upload file produk digital dengan ukuran 5-10MB untuk memastikan konfigurasi berhasil.

## Troubleshooting

### Masih tidak bisa upload?
1. Pastikan Apache sudah direstart
2. Cek apakah ada file `.user.ini` di folder `public/` yang mungkin override setting
3. Buka browser dalam mode incognito untuk menghindari cache
4. Cek error log Laravel di `storage/logs/laravel.log`

### Cara cek konfigurasi PHP aktif:
Buat file `info.php` di folder `public/`:
```php
<?php
phpinfo();
?>
```

Kemudian akses `http://localhost/info.php` dan cari nilai:
- `upload_max_filesize`
- `post_max_size`

**PENTING:** Hapus file `info.php` setelah selesai mengecek untuk keamanan.
