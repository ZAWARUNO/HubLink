# Fitur Statistik Dashboard

## Deskripsi

Halaman statistik dashboard yang menampilkan analisis data bisnis dengan chart yang menarik dan ringan menggunakan Chart.js.

## Fitur yang Tersedia

### 1. Statistik Utama

-   **Total Pengunjung**: Menampilkan jumlah total pengunjung (simulasi berdasarkan order)
-   **Total Pembelian**: Jumlah transaksi yang berhasil
-   **Total Pendapatan**: Total revenue dari semua domain
-   **Total Produk**: Jumlah produk aktif yang dijual

### 2. Chart Analisis

#### Chart Pendapatan Bulanan

-   Line chart menampilkan trend pendapatan 6 bulan terakhir
-   Warna hijau (#00c499) sesuai dengan brand color
-   Responsive dan interactive

#### Chart Status Pembayaran

-   Doughnut chart menampilkan distribusi status pembayaran
-   3 kategori: Settlement (Berhasil), Pending, Cancel
-   Warna: Hijau, Kuning, Merah

#### Chart Produk Terlaris

-   Bar chart menampilkan top 5 produk berdasarkan jumlah penjualan
-   Data real-time dari database

#### Performa Domain

-   Progress bar menampilkan performa setiap domain
-   Berdasarkan total pendapatan
-   Menampilkan jumlah order dan produk per domain

## Teknologi yang Digunakan

### Backend

-   **Laravel**: Framework PHP
-   **StatisticsController**: Controller untuk mengelola data statistik
-   **Eloquent ORM**: Untuk query database
-   **Carbon**: Untuk manipulasi tanggal

### Frontend

-   **Chart.js**: Library untuk membuat chart yang ringan dan responsif
-   **Tailwind CSS**: Framework CSS untuk styling
-   **Responsive Design**: Optimal di semua device

## File yang Dibuat/Dimodifikasi

### Controller

-   `app/Http/Controllers/CMS/StatisticsController.php` - Controller untuk statistik

### Model (Dimodifikasi)

-   `app/Models/Domain.php` - Ditambahkan relationship dengan orders

### Routes

-   `routes/web.php` - Ditambahkan route untuk statistik

### Views

-   `resources/views/cms/statistics.blade.php` - Halaman statistik utama
-   `resources/views/cms/layouts/app.blade.php` - Ditambahkan menu navigasi

## Cara Akses

1. Login ke CMS HubLink
2. Klik menu "Statistik" di sidebar
3. Atau akses langsung: `/cms/statistics`

## Data yang Ditampilkan

### Real Data

-   Total pembelian (dari tabel orders)
-   Total pendapatan (dari tabel orders dengan status settlement)
-   Total produk (dari tabel components)
-   Data chart pendapatan bulanan
-   Data produk terlaris
-   Performa domain

### Simulasi Data

-   Total pengunjung (simulasi berdasarkan jumlah order x 3-8)

## Responsivitas

-   **Mobile**: Layout single column, chart menyesuaikan ukuran layar
-   **Tablet**: Layout 2 kolom untuk chart
-   **Desktop**: Layout 4 kolom untuk statistik utama, 2 kolom untuk chart

## Performance

-   Chart.js ringan dan cepat loading
-   Data di-cache di controller untuk performa optimal
-   Responsive design tidak mempengaruhi performa chart

## Warna Brand

-   Primary: #00c499 (Hijau)
-   Primary Dark: #00a882
-   Chart menggunakan warna yang konsisten dengan brand

## Browser Support

-   Chrome (recommended)
-   Firefox
-   Safari
-   Edge
-   Mobile browsers

## Future Enhancement

1. **Real-time Analytics**: Implementasi WebSocket untuk update real-time
2. **Export Data**: Fitur export ke PDF/Excel
3. **Date Range Filter**: Filter data berdasarkan periode custom
4. **More Chart Types**: Pie chart, area chart, dll
5. **Advanced Analytics**: Conversion rate, bounce rate, dll
6. **Real Visitor Tracking**: Implementasi Google Analytics atau custom tracking
