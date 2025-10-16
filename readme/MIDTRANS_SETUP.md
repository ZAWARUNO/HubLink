# Midtrans Payment Gateway Integration - Setup Guide

## Overview
Aplikasi ini telah diintegrasikan dengan Midtrans Payment Gateway (Sandbox Mode) untuk memproses pembayaran digital product pada component "Template" di page builder.

## Flow Pembayaran

1. **User membuka domain page** → Melihat component "Template" dengan digital product
2. **User klik button "Buy Now"** → Redirect ke halaman checkout
3. **User mengisi form buyer information** → Nama, email, phone, address
4. **User klik "Proceed to Payment"** → Sistem membuat order dan mendapatkan Snap Token dari Midtrans
5. **Midtrans Snap popup muncul** → User memilih metode pembayaran (Credit Card, Bank Transfer, E-Wallet, dll)
6. **User menyelesaikan pembayaran** → Midtrans mengirim notification ke callback endpoint
7. **Redirect ke success/failed page** → Berdasarkan status pembayaran

## Setup Instructions

### 1. Daftar Akun Midtrans Sandbox

1. Buka https://dashboard.sandbox.midtrans.com/register
2. Daftar dengan email Anda
3. Verifikasi email dan login ke dashboard

### 2. Dapatkan API Keys

1. Login ke Midtrans Sandbox Dashboard
2. Pergi ke **Settings** → **Access Keys**
3. Copy **Server Key** dan **Client Key**

### 3. Konfigurasi Environment Variables

Edit file `.env` Anda dan tambahkan:

```env
# Midtrans Payment Gateway (Sandbox)
MIDTRANS_SERVER_KEY=SB-Mid-server-xxxxxxxxxxxxxxxx
MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxxxxxxxxxxxxxx
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

**Penting:** Ganti `xxxxxxxxxxxxxxxx` dengan API keys Anda yang sebenarnya.

### 4. Setup Notification URL di Midtrans Dashboard

1. Login ke Midtrans Sandbox Dashboard
2. Pergi ke **Settings** → **Configuration**
3. Set **Payment Notification URL** ke: `https://your-domain.com/payment/callback`
4. Set **Finish Redirect URL** ke: `https://your-domain.com/payment/success/{order_id}`
5. Set **Unfinish Redirect URL** ke: `https://your-domain.com/payment/failed/{order_id}`
6. Set **Error Redirect URL** ke: `https://your-domain.com/payment/failed/{order_id}`

**Note:** Untuk development lokal, Anda bisa menggunakan ngrok atau expose untuk mendapatkan public URL.

### 5. Testing dengan Sandbox

Midtrans Sandbox menyediakan test credentials untuk berbagai metode pembayaran:

#### Credit Card Test
- **Card Number:** 4811 1111 1111 1114
- **CVV:** 123
- **Exp Date:** 01/25
- **3DS:** 112233

#### E-Wallet Test (GoPay, ShopeePay, dll)
- Gunakan nomor HP test: 08123456789
- OTP akan muncul di simulator

#### Bank Transfer Test
- Virtual Account akan langsung ter-generate
- Gunakan simulator untuk mark as paid

Dokumentasi lengkap test credentials: https://docs.midtrans.com/docs/testing-payment-on-sandbox

## Database Schema

### Orders Table
Tabel `orders` menyimpan semua transaksi:

- `order_id`: Unique order identifier
- `domain_id`: Domain yang menjual product
- `component_id`: Component template yang dibeli
- `customer_name`, `customer_email`, `customer_phone`, `customer_address`: Info pembeli
- `product_title`, `product_description`: Info produk
- `amount`: Harga produk
- `transaction_status`: Status pembayaran (pending, settlement, cancel, expire, deny)
- `transaction_id`: Transaction ID dari Midtrans
- `payment_type`: Metode pembayaran yang digunakan
- `snap_token`: Token untuk Midtrans Snap
- `paid_at`: Timestamp pembayaran berhasil

## API Endpoints

### Checkout
- **GET** `/{domain}/checkout/{componentId}` - Halaman checkout
- **POST** `/{domain}/checkout/{componentId}` - Process checkout dan dapatkan Snap Token

### Payment Callback
- **POST** `/payment/callback` - Webhook dari Midtrans untuk update status pembayaran

### Payment Result
- **GET** `/payment/success/{orderId}` - Halaman pembayaran berhasil
- **GET** `/payment/failed/{orderId}` - Halaman pembayaran gagal

## Security Notes

1. **Server Key** harus disimpan di `.env` dan **JANGAN** di-commit ke repository
2. Gunakan **Sandbox Mode** untuk development dan testing
3. Untuk production, ganti `MIDTRANS_IS_PRODUCTION=true` dan gunakan Production API Keys
4. Midtrans akan memverifikasi signature pada notification callback untuk keamanan

## Troubleshooting

### Error: "Undefined array key 10023" atau "Failed to create payment"
**Penyebab:** Bug di Midtrans PHP SDK saat mengakses array key yang tidak ada dalam cURL options.

**Solusi (sudah diimplementasikan):**
- Menambahkan `CURLOPT_HTTPHEADER => []` ke cURL options
- Fix sudah diterapkan di `CheckoutController` dan `PaymentCallbackController`
- Clear config cache dengan: `php artisan config:clear`

**Jika masih error, cek:**
1. Pastikan `.env` sudah memiliki Midtrans API keys:
   ```env
   MIDTRANS_SERVER_KEY=SB-Mid-server-xxxxxxxx
   MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxxxxxx
   ```

2. Test konfigurasi dengan command:
   ```bash
   php artisan midtrans:test
   ```

3. Verifikasi API keys di Midtrans Dashboard:
   - Login ke https://dashboard.sandbox.midtrans.com/
   - Pergi ke **Settings** → **Access Keys**
   - Copy ulang Server Key dan Client Key
   - Pastikan menggunakan **Sandbox keys** (dimulai dengan `SB-Mid-`)

4. Cek Laravel log untuk detail error:
   ```bash
   tail -f storage/logs/laravel.log
   ```

### Error: "SSL certificate problem: unable to get local issuer certificate"
**Penyebab:** cURL tidak bisa memverifikasi SSL certificate dari Midtrans API di environment lokal.

**Solusi (sudah diimplementasikan):**
- SSL verification otomatis di-disable untuk sandbox mode
- Kode sudah ditambahkan di `CheckoutController` dan `PaymentCallbackController`
- Hanya berlaku saat `MIDTRANS_IS_PRODUCTION=false`

**⚠️ PENTING:** SSL verification akan tetap aktif di production mode untuk keamanan!

### Error: "Midtrans configuration not found"
- Pastikan `.env` sudah dikonfigurasi dengan benar
- Jalankan `php artisan config:clear` untuk clear cache

### Snap popup tidak muncul
- Cek console browser untuk error
- Pastikan Midtrans Snap JS sudah ter-load
- Verify Client Key sudah benar

### Notification callback tidak diterima
- Pastikan Notification URL sudah di-set di Midtrans Dashboard
- Untuk local development, gunakan ngrok atau expose
- Cek log di `storage/logs/laravel.log`

### Payment status tidak update / Status masih "pending" padahal sudah bayar
**Penyebab:** Notification callback dari Midtrans tidak sampai ke server (common di local development)

**Solusi (sudah diimplementasikan):**
- System akan **auto-check status** ke Midtrans saat user buka success page atau download page
- Jika status di Midtrans sudah "settlement", order akan di-update otomatis
- Tidak perlu manual update database

**Untuk Production:**
- Setup Notification URL di Midtrans Dashboard
- Pastikan server bisa diakses public
- Gunakan ngrok/expose untuk local testing
- Check log: `storage/logs/laravel.log` untuk notification dari Midtrans

**Manual Check (jika diperlukan):**
```sql
-- Check order status
SELECT order_id, transaction_status, paid_at FROM orders WHERE order_id = 'ORDER-xxx';

-- Manual update jika perlu (HANYA untuk testing)
UPDATE orders SET transaction_status = 'settlement', paid_at = NOW() WHERE order_id = 'ORDER-xxx';
```

## Digital Product Delivery

### Upload Digital Product

1. Di Page Builder, tambahkan component "Template"
2. Klik Edit pada component tersebut
3. Upload digital product file (PDF, ZIP, etc.) di properties panel
4. File akan disimpan di `storage/app/digital-products/`

### Download Flow untuk Pembeli

1. **Pembeli selesai pembayaran** → Redirect ke Payment Success page
2. **Payment Success page** → Menampilkan tombol "Download Now"
3. **Klik Download Now** → Redirect ke Download page dengan secure token
4. **Download page** → Pembeli bisa download product dengan klik "Download Product"

### Security Features

- ✅ **Token-based authentication** - Setiap download link memiliki unique token
- ✅ **Time-limited access** - Download link valid 24 jam
- ✅ **Payment verification** - Hanya order yang sudah paid bisa download
- ✅ **Download logging** - Semua download di-log untuk tracking

### Download URLs

- **Download Page:** `/download/{orderId}`
- **Direct Download:** `/download/{orderId}/{token}`

Token di-generate otomatis saat pembeli mengakses download page.

## Production Checklist

Sebelum go live ke production:

- [ ] Daftar akun Midtrans Production
- [ ] Dapatkan Production API Keys
- [ ] Update `.env` dengan Production keys
- [ ] Set `MIDTRANS_IS_PRODUCTION=true`
- [ ] Update Notification URL di Production Dashboard
- [ ] Test dengan real payment methods
- [ ] Setup email notification untuk customer dengan download link
- [ ] Setup admin notification untuk new orders
- [ ] Test digital product upload dan download flow
- [ ] Setup automatic backup untuk digital products

## Support

- Midtrans Documentation: https://docs.midtrans.com/
- Midtrans Support: support@midtrans.com
- Midtrans Sandbox Dashboard: https://dashboard.sandbox.midtrans.com/
- Midtrans Production Dashboard: https://dashboard.midtrans.com/
