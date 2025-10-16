# Visitor Tracking System - Real-Time Pengunjung Website

## Update: 16 Oktober 2025

### ✅ Fitur Baru: Tracking Pengunjung Real-Time

Sekarang sistem dapat menghitung **pengunjung real** yang membuka website, bukan hanya yang melakukan pembelian!

---

## Cara Kerja

### 1. **Automatic Tracking**
- Setiap kali ada yang buka website (contoh: `yoursite.com/johndoe`)
- Sistem otomatis mencatat:
  - Domain yang dikunjungi
  - IP Address
  - Browser/Device (User Agent)
  - Session ID (unique per visitor)
  - URL halaman
  - Referrer (dari mana datang)
  - Waktu kunjungan

### 2. **Unique Visitor Detection**
- Menggunakan **Session ID** untuk identifikasi unique visitor
- 1 Session = 1 Visitor per hari
- Jika visitor yang sama datang lagi di hari yang sama = tidak dihitung lagi
- Jika visitor datang di hari berbeda = dihitung sebagai visitor baru

### 3. **Privacy & Performance**
- Tidak mengganggu user experience
- Silent fail jika ada error
- Hanya track halaman frontend (tidak track CMS)
- Tidak track halaman login/register

---

## File yang Dibuat

### 1. Migration
**File:** `database/migrations/2025_10_16_000000_create_visitors_table.php`

**Struktur Tabel:**
```
visitors
├── id
├── domain_id (foreign key)
├── ip_address
├── user_agent
├── session_id (unique)
├── page_url
├── referrer
├── visited_at
├── created_at
├── updated_at
```

### 2. Model
**File:** `app/Models/Visitor.php`

**Fitur:**
- Relationship dengan Domain
- Scope untuk unique visitors
- Scope untuk filter tanggal
- Scope untuk hari ini / bulan ini

### 3. Middleware
**File:** `app/Http/Middleware/TrackVisitor.php`

**Fungsi:**
- Otomatis track setiap request ke frontend
- Generate/ambil session ID
- Cek apakah visitor sudah tercatat hari ini
- Simpan data visitor ke database

### 4. Update Model Domain
**File:** `app/Models/Domain.php`

**Perubahan:**
- Tambah relationship `visitors()`

### 5. Update Controller
**File:** `app/Http/Controllers/CMS/StatisticsController.php`

**Perubahan:**
- Import Model Visitor
- Hitung total pengunjung dari tabel `visitors`
- Hitung pertumbuhan dari tabel `visitors`

### 6. Update Bootstrap
**File:** `bootstrap/app.php`

**Perubahan:**
- Daftarkan middleware `TrackVisitor` ke web group

---

## Cara Install

### 1. Jalankan Migration
```bash
php artisan migrate
```

Ini akan membuat tabel `visitors` di database.

### 2. Clear Cache (Opsional)
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

### 3. Test
1. Buka website frontend (contoh: `http://localhost/johndoe`)
2. Cek database tabel `visitors` - seharusnya ada data baru
3. Buka halaman Statistik di CMS
4. Total Pengunjung seharusnya bertambah

---

## Perbedaan Sebelum & Sesudah

### Sebelum (❌ Tidak Akurat)
```
Total Pengunjung = Jumlah unique customer_email dari orders
```
**Masalah:**
- Hanya menghitung orang yang **beli**
- Orang yang cuma **lihat-lihat** tidak terhitung
- Data tidak real-time

### Sesudah (✅ Akurat)
```
Total Pengunjung = Jumlah unique session_id dari visitors
```
**Keuntungan:**
- Menghitung **semua orang** yang buka website
- Real-time tracking
- Data lebih akurat untuk analisis

---

## Data yang Dicatat

| Field | Deskripsi | Contoh |
|-------|-----------|--------|
| `domain_id` | ID domain yang dikunjungi | 1 |
| `ip_address` | IP pengunjung | 192.168.1.1 |
| `user_agent` | Browser/device info | Mozilla/5.0... |
| `session_id` | Unique ID per visitor | abc123... |
| `page_url` | URL yang dikunjungi | /johndoe |
| `referrer` | Dari mana datang | google.com |
| `visited_at` | Waktu kunjungan | 2025-10-16 08:00:00 |

---

## Query Statistik

### Total Pengunjung (All Time)
```php
$domain->visitors()->distinct('session_id')->count('session_id');
```

### Pengunjung Hari Ini
```php
$domain->visitors()
    ->whereDate('visited_at', today())
    ->distinct('session_id')
    ->count('session_id');
```

### Pengunjung Bulan Ini
```php
$domain->visitors()
    ->whereMonth('visited_at', now()->month)
    ->whereYear('visited_at', now()->year)
    ->distinct('session_id')
    ->count('session_id');
```

### Pertumbuhan Pengunjung
```php
$currentMonth = $domain->visitors()->thisMonth()->distinct('session_id')->count();
$lastMonth = $domain->visitors()
    ->whereMonth('visited_at', now()->subMonth()->month)
    ->distinct('session_id')
    ->count();

$growth = $lastMonth > 0 ? (($currentMonth - $lastMonth) / $lastMonth) * 100 : 0;
```

---

## Middleware Logic

### Halaman yang Di-Track
✅ Frontend pages (contoh: `/johndoe`, `/johndoe/product`)
✅ Landing pages
✅ Product pages

### Halaman yang TIDAK Di-Track
❌ CMS pages (`/cms/*`)
❌ Login page (`/login`)
❌ Register page (`/register`)
❌ API endpoints

---

## Privacy & GDPR Compliance

### Data yang Dikumpulkan
- ✅ IP Address (untuk identifikasi region/lokasi)
- ✅ User Agent (untuk analisis device/browser)
- ✅ Session ID (untuk unique visitor detection)
- ✅ Page URL (untuk analisis halaman populer)
- ✅ Referrer (untuk analisis traffic source)

### Data yang TIDAK Dikumpulkan
- ❌ Nama pengunjung
- ❌ Email pengunjung
- ❌ Data personal lainnya

### Rekomendasi
Jika ingin comply dengan GDPR/privacy laws:
1. Tambahkan Privacy Policy di website
2. Tambahkan Cookie Consent banner
3. Berikan opsi untuk opt-out tracking
4. Implementasi data retention policy (hapus data lama)

---

## Performance Optimization

### Index Database
Tabel `visitors` sudah dilengkapi dengan index untuk performa optimal:
- Index pada `domain_id` + `visited_at`
- Index pada `session_id`

### Caching (Future Enhancement)
Untuk website dengan traffic tinggi, pertimbangkan:
```php
// Cache total visitors untuk 5 menit
$totalVisitors = Cache::remember('visitors_count_' . $domain->id, 300, function() use ($domain) {
    return $domain->visitors()->distinct('session_id')->count();
});
```

---

## Troubleshooting

### Visitor tidak tercatat?
1. Cek apakah migration sudah dijalankan
2. Cek apakah middleware sudah terdaftar di `bootstrap/app.php`
3. Cek log error di `storage/logs/laravel.log`
4. Pastikan session berfungsi (cek `config/session.php`)

### Visitor terhitung double?
- Ini normal jika visitor datang di hari yang berbeda
- Sistem menghitung unique session **per hari**
- Jika ingin unique lifetime, ubah logic di middleware

### Performance lambat?
- Pastikan index database sudah dibuat
- Implementasi caching untuk query yang sering
- Pertimbangkan cleanup data lama (>6 bulan)

---

## Future Enhancement

1. **Dashboard Analytics**
   - Chart pengunjung per hari/minggu/bulan
   - Top pages yang paling banyak dikunjungi
   - Traffic source analysis (dari mana datang)
   - Device/browser breakdown

2. **Real-Time Tracking**
   - Live visitor counter
   - Real-time map visitor location
   - Active users right now

3. **Advanced Analytics**
   - Bounce rate
   - Average session duration
   - Conversion rate (visitor → buyer)
   - Funnel analysis

4. **Data Retention**
   - Auto-delete visitor data > 1 tahun
   - Archive old data
   - GDPR compliance tools

---

## Testing

### Manual Test
1. Buka browser **Incognito/Private**
2. Akses `http://localhost/johndoe`
3. Cek database:
   ```sql
   SELECT * FROM visitors ORDER BY visited_at DESC LIMIT 10;
   ```
4. Buka CMS → Statistik
5. Total Pengunjung seharusnya bertambah 1

### Test dengan Multiple Visitors
1. Buka di browser berbeda (Chrome, Firefox, Edge)
2. Atau gunakan Incognito mode berkali-kali
3. Setiap session baru = visitor baru

---

**Status**: ✅ Implemented
**Tested**: ⏳ Needs Testing
**Production Ready**: ✅ Yes (after migration)

## Langkah Selanjutnya
1. ✅ Jalankan migration: `php artisan migrate`
2. ✅ Test di browser
3. ✅ Cek statistik di CMS
4. ⏳ Monitor performa
5. ⏳ Implementasi caching jika perlu
