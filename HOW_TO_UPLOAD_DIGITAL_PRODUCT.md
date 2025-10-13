# How to Upload Digital Product - Step by Step

## ⚠️ PENTING: File HARUS di-upload, bukan hanya URL!

Digital product **HARUS** di-upload ke server Laravel, bukan hanya menyimpan URL.

## 📋 Step-by-Step Guide

### 1. Login ke CMS Dashboard
```
URL: http://your-domain.test/cms
```

### 2. Buka Page Builder
- Klik menu "Domains"
- Pilih domain yang ingin di-edit
- Klik tombol "Edit Page" atau "Builder"

### 3. Add/Edit Template Component

**Jika belum ada component:**
- Drag & drop component **"Template"** dari sidebar kiri ke canvas

**Jika sudah ada component:**
- Klik icon **Edit** (pensil) pada component Template

### 4. Upload Digital Product - CRITICAL STEPS!

Di Properties Panel, bagian **"Digital Product"**:

**Step 4.1: Choose File**
```
1. Klik tombol "Choose Digital Product"
2. File picker akan terbuka
3. Pilih file yang ingin dijual (PDF, ZIP, DOC, XLS, atau gambar)
4. File size maksimal: 10 MB
```

**Step 4.2: Upload File** ⚠️ **JANGAN SKIP STEP INI!**
```
1. Setelah pilih file, akan muncul tombol "Upload Product"
2. KLIK tombol "Upload Product" ← PENTING!
3. Progress bar akan muncul
4. Tunggu hingga muncul notifikasi "Digital product uploaded successfully!"
5. Akan muncul checkmark hijau dengan text "Product file uploaded"
```

**Step 4.3: Verify Upload**
```
✓ Harus ada checkmark hijau
✓ Harus ada text "Product file uploaded"
✓ Harus ada file size info
```

**❌ KESALAHAN UMUM:**
- Pilih file tapi TIDAK klik "Upload Product" ← File tidak ter-upload!
- Langsung klik "Save Changes" tanpa upload ← File tidak ter-upload!
- Hanya paste URL di field lain ← File tidak ter-upload!

### 5. Fill Other Fields

Setelah file ter-upload, isi field lainnya:
- **Image**: URL gambar preview product
- **Title**: Nama product
- **Description**: Deskripsi product
- **Price**: Harga dalam Rupiah (contoh: 50000)
- **Button Text**: Text tombol (default: "Buy Now")

### 6. Save Changes

**PENTING:**
```
1. Klik tombol "Save Changes" di bawah form
2. Tunggu notifikasi "Component updated successfully"
3. Jangan close panel sebelum muncul notifikasi
```

### 7. Publish

```
1. Klik tombol "Publish" di kanan atas
2. Tunggu konfirmasi
3. Component sekarang live!
```

## ✅ Verification Checklist

Setelah upload, verify dengan command:

```bash
# Check apakah file benar-benar ter-upload
php artisan order:debug

# Atau check manual
ls storage/app/digital-products/
```

**Harus ada file dengan nama hash, contoh:**
```
NxwbvKAvi38I98ZhO4MpXOOvtvxb5XDQqzj9FiNp.pdf
```

## 🔍 Troubleshooting

### Upload Button Tidak Muncul
**Problem**: Tombol "Upload Product" tidak muncul setelah pilih file

**Solution**:
- Refresh page
- Pilih file lagi
- Check console browser untuk error (F12 → Console tab)

### Upload Gagal
**Problem**: Error saat upload atau progress bar stuck

**Solution**:
1. Check file size (max 10 MB)
2. Check file type (PDF, ZIP, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG, GIF)
3. Check Laravel logs:
   ```bash
   tail -f storage/logs/laravel.log
   ```
4. Check folder permissions:
   ```bash
   # Windows (PowerShell as Admin)
   icacls storage\app\digital-products /grant Users:F
   
   # Linux/Mac
   chmod 755 storage/app/digital-products
   ```

### File Tidak Ada di Storage
**Problem**: Upload success tapi file tidak ada di `storage/app/digital-products/`

**Solution**:
1. Check apakah benar-benar klik "Upload Product"
2. Check Laravel logs untuk error
3. Verify folder exists:
   ```bash
   mkdir storage/app/digital-products
   ```
4. Try upload ulang

### Download Masih 404
**Problem**: Setelah upload, download masih 404

**Solution**:
1. **Verify file exists:**
   ```bash
   php artisan order:debug ORDER-xxx
   ```
   Harus show: `File Exists: Yes`

2. **Check component digital_product_path:**
   ```sql
   SELECT id, type, digital_product_path 
   FROM components 
   WHERE type = 'template';
   ```
   Harus ada value di `digital_product_path`

3. **If path is null, upload ulang:**
   - Edit component di Page Builder
   - Upload digital product lagi
   - Klik "Save Changes"

## 📸 Visual Guide

### Correct Upload Flow:

```
1. Choose Digital Product
   ↓
2. [File Selected: example.pdf]
   ↓
3. Click "Upload Product" ← CRITICAL!
   ↓
4. [Progress Bar: ████████ 100%]
   ↓
5. ✓ Product file uploaded
   ↓
6. Fill other fields
   ↓
7. Click "Save Changes"
   ↓
8. ✓ Component updated successfully
```

### Wrong Flow (File NOT uploaded):

```
1. Choose Digital Product
   ↓
2. [File Selected: example.pdf]
   ↓
3. ❌ Skip "Upload Product" button
   ↓
4. Click "Save Changes" directly
   ↓
5. ❌ File NOT uploaded to server!
   ↓
6. Download will fail with 404
```

## 🎯 Testing

After upload, test the complete flow:

1. **Create test order:**
   - Go to public page
   - Click "Buy Now"
   - Fill checkout form
   - Complete payment (sandbox)

2. **Verify download:**
   - After payment success
   - Click "Download Now"
   - Should redirect to download page
   - Click "Download Product"
   - File should download ✓

## 💡 Best Practices

1. **Always upload actual product file**
   - Don't just save URL
   - Don't skip upload step
   - Wait for upload confirmation

2. **Test before going live**
   - Upload test file
   - Complete test purchase
   - Verify download works

3. **Backup digital products**
   ```bash
   tar -czf digital-products-backup.tar.gz storage/app/digital-products/
   ```

4. **Monitor storage space**
   ```bash
   du -sh storage/app/digital-products/
   ```

## 📞 Still Having Issues?

If upload still not working:

1. Check Laravel logs:
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. Check browser console (F12):
   - Look for JavaScript errors
   - Check network tab for failed requests

3. Verify route exists:
   ```bash
   php artisan route:list | grep digital
   ```
   Should show: `POST /cms/builder/{domainId}/upload-digital-product`

4. Test upload manually with curl:
   ```bash
   curl -X POST http://your-domain.test/cms/builder/1/upload-digital-product \
     -H "X-CSRF-TOKEN: your-token" \
     -F "file=@/path/to/test.pdf"
   ```

---

**Remember:** The file MUST be uploaded to the server, not just referenced by URL!
