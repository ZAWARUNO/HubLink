# Fix Duplicate Products Issue

## Masalah
Saat menambahkan component "Template" baru dari page builder, produk muncul 2 kali (duplicate).

## ✅ Penyebab
Di file `app/Http/Controllers/CMS/BuilderController.php`, method `createProductFromTemplate()` melakukan:
1. Update component yang sudah ada ✅
2. Membuat component template BARU lagi ❌ (ini yang menyebabkan duplikasi)

## ✅ Solusi yang Sudah Diterapkan
File `BuilderController.php` sudah diperbaiki:
- Menghapus kode yang membuat component duplikat
- Sekarang hanya update component yang sudah ada
- Tidak ada lagi duplikasi produk

## Cara Membersihkan Produk yang Sudah Terduplikasi

### Opsi 1: Manual dari CMS
1. Buka halaman **Produk** di CMS
2. Hapus produk yang duplikat secara manual
3. Pastikan hanya menyisakan 1 produk untuk setiap item

### Opsi 2: Menggunakan Tinker (Advanced)
Jika ada banyak produk duplikat, Anda bisa menggunakan Laravel Tinker:

```bash
php artisan tinker
```

Kemudian jalankan:

```php
// Lihat semua template components
$templates = App\Models\Component::where('type', 'template')->get();

// Cek jumlah
echo "Total templates: " . $templates->count();

// Untuk melihat detail
foreach($templates as $t) {
    echo "ID: {$t->id} - Title: {$t->properties['title']} - Domain: {$t->domain_id}\n";
}

// Jika ingin hapus yang duplikat (HATI-HATI!)
// Contoh: hapus template dengan ID tertentu
// App\Models\Component::find(ID_YANG_DUPLIKAT)->delete();
```

### Opsi 3: Query Database Langsung
Jika menggunakan database viewer (phpMyAdmin, TablePlus, dll):

```sql
-- Lihat semua template components
SELECT id, domain_id, properties, created_at 
FROM components 
WHERE type = 'template' 
ORDER BY created_at DESC;

-- Hapus berdasarkan ID (ganti XX dengan ID yang duplikat)
-- DELETE FROM components WHERE id = XX;
```

## Pencegahan
✅ Bug sudah diperbaiki di `BuilderController.php`
✅ Produk baru tidak akan terduplikasi lagi
✅ Anda bisa menambahkan template baru dengan aman

## Testing
Setelah perbaikan ini:
1. Buka page builder
2. Tambahkan component "Template" baru
3. Isi data produk
4. Cek halaman Produk - seharusnya hanya muncul 1x
5. Cek page builder - component juga hanya 1x

---
**Update**: 16 Oktober 2025
**Status**: ✅ Fixed
