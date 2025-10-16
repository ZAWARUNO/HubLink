<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Statistik - {{ $exportDate }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            background: #f5f5f5;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 3px solid #00c499;
        }
        .header h1 {
            color: #333;
            font-size: 28px;
            margin-bottom: 10px;
        }
        .header p {
            color: #666;
            font-size: 14px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 40px;
        }
        .stat-card {
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
            border-left: 4px solid #00c499;
        }
        .stat-card h3 {
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
            font-weight: normal;
        }
        .stat-card .value {
            color: #333;
            font-size: 32px;
            font-weight: bold;
        }
        .section {
            margin-bottom: 40px;
        }
        .section h2 {
            color: #333;
            font-size: 20px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e5e5e5;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th {
            background: #00c499;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: 600;
        }
        table td {
            padding: 12px;
            border-bottom: 1px solid #e5e5e5;
        }
        table tr:hover {
            background: #f9f9f9;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e5e5e5;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .container {
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>📊 Laporan Statistik Dashboard</h1>
            <p>Diekspor pada: {{ $exportDate }}</p>
            <p>User: {{ $user->name }} ({{ $user->email }})</p>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Pengunjung</h3>
                <div class="value">{{ number_format($totalVisitors) }}</div>
            </div>
            <div class="stat-card">
                <h3>Total Pembelian</h3>
                <div class="value">{{ number_format($totalPurchases) }}</div>
            </div>
            <div class="stat-card">
                <h3>Total Pendapatan</h3>
                <div class="value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            </div>
            <div class="stat-card">
                <h3>Total Produk</h3>
                <div class="value">{{ number_format($totalProducts) }}</div>
            </div>
        </div>

        <!-- Domain Performance -->
        <div class="section">
            <h2>Performa Domain</h2>
            <table>
                <thead>
                    <tr>
                        <th>Domain</th>
                        <th>Pengunjung</th>
                        <th>Pesanan</th>
                        <th>Produk</th>
                        <th>Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($domains as $domain)
                    <tr>
                        <td><strong>{{ $domain->title ?? $domain->slug }}</strong></td>
                        <td>{{ number_format($domain->visitors()->distinct('session_id')->count()) }}</td>
                        <td>{{ number_format($domain->orders()->where('transaction_status', 'settlement')->count()) }}</td>
                        <td>{{ number_format($domain->components()->where('type', 'template')->count()) }}</td>
                        <td>Rp {{ number_format($domain->orders()->where('transaction_status', 'settlement')->sum('amount'), 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: #999;">Tidak ada data domain</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Recent Orders -->
        <div class="section">
            <h2>Transaksi Terbaru (10 Terakhir)</h2>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Produk</th>
                        <th>Customer</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $recentOrders = \App\Models\Order::whereIn('domain_id', $domains->pluck('id'))
                            ->orderBy('created_at', 'desc')
                            ->limit(10)
                            ->get();
                    @endphp
                    @forelse($recentOrders as $order)
                    <tr>
                        <td><strong>{{ $order->order_id }}</strong></td>
                        <td>{{ $order->product_title }}</td>
                        <td>{{ $order->customer_name }}</td>
                        <td>Rp {{ number_format($order->amount, 0, ',', '.') }}</td>
                        <td>
                            @if($order->transaction_status == 'settlement')
                                <span style="color: #10B981;">✓ Berhasil</span>
                            @elseif($order->transaction_status == 'pending')
                                <span style="color: #F59E0B;">⏳ Pending</span>
                            @else
                                <span style="color: #EF4444;">✗ {{ ucfirst($order->transaction_status) }}</span>
                            @endif
                        </td>
                        <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: #999;">Belum ada transaksi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Laporan ini digenerate otomatis oleh HubLink CMS</p>
            <p>© {{ date('Y') }} HubLink. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
