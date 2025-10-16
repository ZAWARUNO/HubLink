# Digital Product Upload & Delivery Guide

## Cara Upload Digital Product

### 1. Buat Component Template
1. Login ke CMS Dashboard
2. Buka Page Builder untuk domain Anda
3. Drag & drop component **"Template"** ke canvas
4. Klik **Edit** pada component tersebut

### 2. Upload Digital Product
1. Di Properties Panel, scroll ke bagian **"Digital Product"**
2. Klik tombol **"Choose Digital Product"**
3. Pilih file digital product Anda (PDF, ZIP, DOC, XLS, atau gambar)
4. Klik **"Upload Product"** (tombol akan muncul setelah file dipilih)
5. Tunggu hingga upload selesai (progress bar akan muncul)
6. Setelah berhasil, akan muncul notifikasi "Product file uploaded"

### 3. Save Component
1. Isi informasi product lainnya:
   - **Image**: Gambar preview product
   - **Title**: Nama product
   - **Description**: Deskripsi product
   - **Price**: Harga dalam Rupiah
   - **Button Text**: Text tombol (default: "Buy Now")
2. Klik **"Save Changes"**

### 4. Publish
1. Klik tombol **"Publish"** di kanan atas
2. Component Template dengan digital product sudah live!

## Flow Pembeli

### 1. Pembeli Melihat Product
- Pembeli membuka domain page Anda
- Melihat component Template dengan product yang dijual
- Klik tombol "Buy Now"

### 2. Checkout & Payment
- Redirect ke halaman checkout
- Isi data buyer (nama, email, phone, address)
- Klik "Proceed to Payment"
- Midtrans Snap popup muncul
- Pilih metode pembayaran dan selesaikan transaksi

### 3. Download Product
Setelah pembayaran berhasil, pembeli akan:
1. **Redirect ke Payment Success page**
   - Muncul tombol hijau **"Download Now"**
   - Klik tombol tersebut

2. **Masuk ke Download page**
   - Melihat detail product dan order
   - Klik tombol **"Download Product"**
   - File akan ter-download otomatis

3. **Akses Ulang**
   - Pembeli bisa akses download page kapan saja via URL: `/download/{orderId}`
   - Download link valid selama **24 jam**
   - Setelah 24 jam, token akan di-regenerate otomatis

## Security Features

### Token-Based Download
- Setiap download link memiliki **unique token**
- Token valid selama **24 jam** dari waktu purchase
- Token otomatis di-regenerate jika expired
- Hanya order yang sudah **paid** bisa download

### File Storage
- Digital products disimpan di `storage/app/digital-products/`
- File tidak bisa diakses langsung via URL
- Harus melalui download controller dengan token verification

### Download Logging
- Setiap download di-log ke `storage/logs/laravel.log`
- Log mencatat: order_id, customer_email, product_title, timestamp

## File Types & Limits

### Supported File Types
- **Documents**: PDF, DOC, DOCX, XLS, XLSX
- **Archives**: ZIP
- **Images**: JPG, JPEG, PNG, GIF

### File Size Limit
- Maximum: **10 MB** per file
- Untuk file lebih besar, pertimbangkan untuk compress atau split

## Troubleshooting

### Upload Gagal
**Problem**: "Failed to store file"
**Solution**:
- Pastikan folder `storage/app/digital-products/` exists dan writable
- Buat folder jika belum ada:
  ```bash
  mkdir -p storage/app/digital-products
  chmod 755 storage/app/digital-products
  ```
- Jalankan: `php artisan storage:link`
- Check file size tidak melebihi 10MB

### Digital Product Path Tidak Tersimpan
**Problem**: File ter-upload tapi tidak masuk ke kolom `digital_product_path`
**Solution**:
- Pastikan sudah klik "Save Changes" setelah upload
- Check migration sudah dijalankan: `php artisan migrate`
- Clear cache: `php artisan config:clear`

### Download Link Tidak Muncul
**Problem**: Tombol "Download Now" tidak muncul di Payment Success page
**Solution**:
- Pastikan component memiliki `digital_product_path` yang valid
- Check order status adalah "settlement" (paid)
- Verify file masih ada di storage

### Download Error 404 - File Not Found
**Problem**: Error 404 saat klik "Download Product"
**Solution**:
1. **Check apakah file ada:**
   ```bash
   php artisan order:debug ORDER-xxx
   ```
   
2. **Jika folder tidak ada, buat:**
   ```bash
   mkdir storage/app/digital-products
   ```
   
3. **Upload ulang digital product:**
   - Buka Page Builder
   - Edit component Template
   - Upload digital product lagi
   - Save Changes
   
4. **Verify file exists:**
   - Check `storage/app/digital-products/` folder
   - File harus ada dengan nama yang sama seperti di database

### TypeError: array_merge() Argument #1 must be of type array
**Problem**: Error saat generate download token
**Solution (sudah diimplementasikan)**:
- Order model sekarang menggunakan accessor/mutator untuk handle `midtrans_response`
- Otomatis convert string JSON ke array saat read
- Otomatis convert array ke JSON string saat save
- Migration sudah dijalankan untuk fix data lama

### Download Token Expired
**Problem**: "Download token has expired"
**Solution**:
- Akses ulang download page: `/download/{orderId}`
- Token akan di-regenerate otomatis
- Valid untuk 24 jam berikutnya

## Database Schema

### Components Table
```
digital_product_path: string (nullable)
- Menyimpan path file di storage
- Format: "digital-products/filename.ext"
```

### Orders Table
```
midtrans_response: json
- Menyimpan download_token
- Menyimpan download_token_expires_at
```

## API Endpoints

### Upload Digital Product
```
POST /cms/builder/{domainId}/upload-digital-product
Body: multipart/form-data
  - file: File (max 10MB)
Response:
  - path: string
  - originalName: string
  - fileType: string
  - fileSize: integer
```

### Download Page
```
GET /download/{orderId}
- Generate/refresh download token
- Show download page with product info
```

### Direct Download
```
GET /download/{orderId}/{token}
- Verify token and payment status
- Download file
```

## Best Practices

### Untuk Seller
1. **Test download flow** sebelum publish
2. **Backup digital products** secara berkala
3. **Monitor download logs** untuk tracking
4. **Update product** jika ada versi baru
5. **Provide support email** untuk customer

### Untuk Pembeli
1. **Download segera** setelah pembayaran berhasil
2. **Save file** ke lokasi yang aman
3. **Check email** untuk download link backup
4. **Contact seller** jika ada masalah download
5. **Download dalam 24 jam** untuk menghindari token expired

## Notes

- Download link dikirim via email (implementasi email notification coming soon)
- Pembeli bisa download berkali-kali dalam periode 24 jam
- Setelah 24 jam, token di-regenerate otomatis saat akses download page
- File digital product tidak bisa diakses tanpa valid token
- Seller bisa update digital product kapan saja via Page Builder

## Support

Jika ada pertanyaan atau masalah:
1. Check troubleshooting guide di atas
2. Check Laravel logs: `storage/logs/laravel.log`
3. Contact developer untuk bantuan teknis
