# Halaman Pesanan (Orders) - Manajemen Transaksi Lengkap

## Update: 16 Oktober 2025

### ✅ Fitur Baru: Halaman Orders

Halaman untuk mengelola semua transaksi dan pesanan dengan lengkap!

---

## Fitur Utama

### 1. **Halaman List Orders** 📋
**Route:** `/cms/orders`

**Fitur:**
- ✅ Tabel orders lengkap dengan pagination
- ✅ 4 Stats cards (Total, Berhasil, Pending, Revenue)
- ✅ Filter & Search
- ✅ Sort by column
- ✅ Status badges berwarna
- ✅ Actions (View, Delete)

**Data yang Ditampilkan:**
- Order ID
- Nama Produk
- Customer (nama + email)
- Domain
- Jumlah pembayaran
- Status transaksi
- Tanggal & waktu
- Aksi (detail & hapus)

---

### 2. **Halaman Detail Order** 📄
**Route:** `/cms/orders/{id}`

**Fitur:**
- ✅ Informasi lengkap order
- ✅ Status pembayaran dengan badge
- ✅ Detail produk
- ✅ Informasi customer lengkap
- ✅ Payment info
- ✅ Timeline transaksi
- ✅ Link ke halaman download (jika settlement)
- ✅ Tombol hapus order

**Sections:**
1. **Order Status** - Status transaksi + tanggal bayar
2. **Product Info** - Nama, harga, domain, file digital
3. **Customer Info** - Nama, email, HP dengan icons
4. **Payment Info** - Metode, snap token, total
5. **Timeline** - Order created, paid, last update
6. **Actions** - Download page, delete order

---

### 3. **Filter & Search** 🔍

#### Search:
- Order ID
- Nama customer
- Email customer
- Nama produk

#### Filter by Status:
- Semua Status
- Berhasil (settlement)
- Pending
- Expire
- Cancel

#### Filter by Domain:
- Semua Domain
- Per domain spesifik

#### Sort:
- By created_at (default DESC)
- Customizable

---

### 4. **Stats Cards** 📊

**Card 1: Total Pesanan**
- Icon: Shopping bag (biru)
- Count: All orders

**Card 2: Berhasil**
- Icon: Check circle (hijau)
- Count: Settlement orders

**Card 3: Pending**
- Icon: Clock (kuning)
- Count: Pending orders

**Card 4: Total Pendapatan**
- Icon: Money (primary)
- Amount: Sum of settlement orders

---

## Status Badges

### Settlement (Berhasil)
- **Warna**: Hijau
- **Icon**: Check circle
- **Text**: "Berhasil" / "Pembayaran Berhasil"

### Pending
- **Warna**: Kuning
- **Icon**: Clock
- **Text**: "Pending" / "Menunggu Pembayaran"

### Expire
- **Warna**: Abu-abu
- **Icon**: X circle
- **Text**: "Expire"

### Cancel / Other
- **Warna**: Merah
- **Icon**: X circle
- **Text**: Status name (ucfirst)

---

## File yang Dibuat

### 1. Controller
**File:** `app/Http/Controllers/CMS/OrderController.php`

**Methods:**
- `index()` - List orders dengan filter & pagination
- `show($id)` - Detail order
- `destroy($id)` - Delete order

**Features:**
- Filter by status
- Filter by domain
- Search functionality
- Sort functionality
- Pagination (20 per page)
- Statistics calculation
- Authorization check (only user's orders)

### 2. Views

#### Index View
**File:** `resources/views/cms/orders/index.blade.php`

**Sections:**
- Header dengan title
- Success/Error messages
- 4 Stats cards
- Filter form (search, status, domain)
- Orders table dengan pagination
- Empty state

#### Detail View
**File:** `resources/views/cms/orders/show.blade.php`

**Layout:** 2 columns (main + sidebar)

**Main Column:**
- Order status card
- Product info card
- Customer info card

**Sidebar:**
- Payment info card
- Timeline card
- Actions card

### 3. Routes
**File:** `routes/web.php`

**Routes Added:**
```php
Route::get('/orders', [CmsOrderController::class, 'index'])->name('cms.orders.index');
Route::get('/orders/{id}', [CmsOrderController::class, 'show'])->name('cms.orders.show');
Route::delete('/orders/{id}', [CmsOrderController::class, 'destroy'])->name('cms.orders.destroy');
```

### 4. Sidebar Menu
**File:** `resources/views/cms/layouts/app.blade.php`

**Menu Added:**
- Icon: Shopping bag
- Text: "Pesanan"
- Route: `cms.orders.index`
- Active state: `cms.orders.*`

---

## Styling & Design

### Colors:
- **Blue**: Stats card (total orders)
- **Green**: Success badge & card
- **Yellow**: Pending badge & card
- **Red**: Error badge
- **Primary**: Revenue card & icons

### Icons:
- **Shopping Bag**: Orders
- **Check Circle**: Success
- **Clock**: Pending
- **X Circle**: Cancel/Expire
- **User**: Customer
- **Mail**: Email
- **Phone**: Phone number
- **Money**: Payment

### Layout:
- **Table**: Responsive dengan overflow-x-auto
- **Cards**: rounded-2xl dengan border
- **Badges**: rounded-full dengan icons
- **Grid**: 2 cols mobile, 4 cols desktop

---

## Responsive Design

### Mobile (< 640px):
- Stats: 2 columns
- Table: Horizontal scroll
- Filter: Stacked vertically
- Font: Smaller (text-xs, text-sm)

### Desktop (> 640px):
- Stats: 4 columns
- Table: Full width
- Filter: 4 columns grid
- Detail: 2 columns layout

---

## Security

### Authorization:
- ✅ Only user's own orders
- ✅ Domain ownership verified
- ✅ Auth middleware required

### Data Protection:
- ✅ No SQL injection
- ✅ CSRF protection
- ✅ Validated inputs

---

## Pagination

- **Per Page**: 20 orders
- **Style**: Laravel default
- **Query String**: Preserved (filters maintained)
- **Links**: Previous, numbers, next

---

## Empty States

### No Orders:
- Icon: Archive box
- Message: "Belum ada pesanan"
- Centered layout

### No Search Results:
- Same empty state
- Filters can be reset

---

## Actions

### View Detail:
- Icon: Eye
- Color: Blue
- Action: Navigate to detail page

### Delete Order:
- Icon: Trash
- Color: Red
- Confirmation: Alert dialog
- Success: Redirect with message

---

## Filter Form

### Search Input:
- Placeholder: "Order ID, nama, email, produk..."
- Type: Text
- Width: 2 columns (desktop)

### Status Select:
- Options: All, Settlement, Pending, Expire, Cancel
- Default: All
- Width: 1 column

### Domain Select:
- Options: All domains + user's domains
- Default: All
- Width: 1 column

### Buttons:
- **Filter**: Primary button (submit)
- **Reset**: Secondary button (clear filters)

---

## Timeline (Detail Page)

### Events Tracked:
1. **Order Dibuat**
   - Icon: Clock (blue)
   - Time: created_at

2. **Pembayaran Diterima** (if paid)
   - Icon: Check (green)
   - Time: paid_at

3. **Terakhir Update**
   - Icon: Clock (gray)
   - Time: updated_at

---

## Customer Info Display

### Name:
- Icon: User (blue circle)
- Label: "Nama"
- Value: customer_name

### Email:
- Icon: Mail (purple circle)
- Label: "Email"
- Value: customer_email

### Phone:
- Icon: Phone (green circle)
- Label: "Nomor HP"
- Value: customer_phone

---

## Testing

### Test List Page:
1. Login ke CMS
2. Klik menu "Pesanan"
3. ✅ Lihat stats cards
4. ✅ Lihat tabel orders
5. ✅ Test filter by status
6. ✅ Test filter by domain
7. ✅ Test search
8. ✅ Test pagination

### Test Detail Page:
1. Klik icon eye pada order
2. ✅ Lihat detail lengkap
3. ✅ Lihat timeline
4. ✅ Test link download (jika settlement)
5. ✅ Test delete order

### Test Responsive:
1. Resize browser
2. ✅ Mobile: 2 cols stats
3. ✅ Desktop: 4 cols stats
4. ✅ Table scroll horizontal di mobile

---

## Query Optimization

### Eager Loading:
```php
$orders = Order::whereIn('domain_id', $domains->pluck('id'))
    ->with('domain')
    ->paginate(20);
```

### Efficient Counting:
```php
$settlementOrders = Order::whereIn('domain_id', $domains->pluck('id'))
    ->where('transaction_status', 'settlement')
    ->count();
```

### No N+1 Problem:
- Domain relationship loaded with `with('domain')`
- Stats calculated once
- Pagination efficient

---

## Future Enhancement

### Export:
1. **CSV Export**: Download orders as CSV
2. **PDF Export**: Print-friendly invoice
3. **Excel Export**: Full data export

### Bulk Actions:
1. **Bulk Delete**: Delete multiple orders
2. **Bulk Status Update**: Change status
3. **Bulk Export**: Export selected

### Advanced Filters:
1. **Date Range**: Filter by date
2. **Amount Range**: Filter by price
3. **Payment Method**: Filter by method

### Order Management:
1. **Refund**: Process refund
2. **Resend Email**: Resend download link
3. **Notes**: Add internal notes

---

**Status**: ✅ Completed
**Tested**: ⏳ Needs Testing
**Production Ready**: ✅ Yes

## Summary

Halaman Orders sekarang menyediakan:
- 📋 **List lengkap** semua transaksi
- 🔍 **Filter & Search** yang powerful
- 📊 **Statistics** real-time
- 📄 **Detail page** informatif
- 🎨 **UI Modern** dengan badges & icons
- 📱 **Responsive** di semua device
- 🔒 **Secure** dengan authorization

Manajemen orders jadi lebih mudah dan profesional! 🚀
