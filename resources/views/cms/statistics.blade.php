@extends('cms.layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Statistik Dashboard</h1>
            <p class="text-gray-600 mt-1">Pantau performa dan analisis bisnis Anda</p>
        </div>
        <div class="flex gap-2">
            <button class="px-4 py-2 bg-white border rounded-lg hover:bg-gray-50 text-sm">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export
            </button>
            <button class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark text-sm">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Refresh
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <div class="bg-white rounded-2xl border p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Pengunjung</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">{{ number_format($totalVisitors) }}</p>
                    <p class="text-green-600 text-xs sm:text-sm mt-1">+12% dari bulan lalu</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Pembelian</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">{{ number_format($totalPurchases) }}</p>
                    <p class="text-green-600 text-xs sm:text-sm mt-1">+8% dari bulan lalu</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m0 0h8.5M9.5 18h8.5"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Pendapatan</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                    <p class="text-green-600 text-xs sm:text-sm mt-1">+15% dari bulan lalu</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Produk</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">{{ number_format($totalProducts) }}</p>
                    <p class="text-gray-500 text-xs sm:text-sm mt-1">Aktif dijual</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Revenue Chart -->
        <div class="bg-white rounded-2xl border p-4 sm:p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Pendapatan Bulanan</h3>
                <select class="text-sm border rounded-lg px-3 py-1">
                    <option>6 Bulan Terakhir</option>
                    <option>12 Bulan Terakhir</option>
                    <option>Tahun Ini</option>
                </select>
            </div>
            <div class="h-64">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Payment Status Chart -->
        <div class="bg-white rounded-2xl border p-4 sm:p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Status Pembayaran</h3>
                <div class="flex gap-2 text-xs">
                    <div class="flex items-center gap-1">
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        <span>Berhasil</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                        <span>Pending</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                        <span>Batal</span>
                    </div>
                </div>
            </div>
            <div class="h-64">
                <canvas id="paymentStatusChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Additional Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Products Chart -->
        <div class="bg-white rounded-2xl border p-4 sm:p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Produk Terlaris</h3>
                <span class="text-sm text-gray-500">Top 5</span>
            </div>
            <div class="h-64">
                <canvas id="topProductsChart"></canvas>
            </div>
        </div>

        <!-- Domain Performance -->
        <div class="bg-white rounded-2xl border p-4 sm:p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Performa Domain</h3>
                <span class="text-sm text-gray-500">Berdasarkan Pendapatan</span>
            </div>
            <div class="space-y-4">
                @forelse($domainPerformance as $domain)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-900">{{ $domain['domain'] }}</p>
                        <p class="text-sm text-gray-500">{{ $domain['orders'] }} pesanan • {{ $domain['products'] }} produk</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-900">Rp {{ number_format($domain['revenue'], 0, ',', '.') }}</p>
                        <div class="w-20 bg-gray-200 rounded-full h-2 mt-1">
                            <div class="bg-primary h-2 rounded-full" style="width: {{ $domainPerformance->max('revenue') > 0 ? ($domain['revenue'] / $domainPerformance->max('revenue')) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <p class="text-gray-500">Belum ada data performa domain</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: @json($months),
            datasets: [{
                label: 'Pendapatan',
                data: @json($revenueData),
                borderColor: '#00c499',
                backgroundColor: 'rgba(0, 196, 153, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#00c499',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            elements: {
                point: {
                    hoverRadius: 8
                }
            }
        }
    });

    // Payment Status Chart
    const paymentStatusCtx = document.getElementById('paymentStatusChart').getContext('2d');
    new Chart(paymentStatusCtx, {
        type: 'doughnut',
        data: {
            labels: @json($paymentStatusLabels),
            datasets: [{
                data: @json($paymentStatusData),
                backgroundColor: @json($paymentStatusColors),
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            cutout: '60%'
        }
    });

    // Top Products Chart
    const topProductsCtx = document.getElementById('topProductsChart').getContext('2d');
    const topProductsData = @json($topProducts);
    
    new Chart(topProductsCtx, {
        type: 'bar',
        data: {
            labels: topProductsData.map(item => item.product_title || 'Produk Tanpa Nama'),
            datasets: [{
                label: 'Jumlah Penjualan',
                data: topProductsData.map(item => item.sales_count),
                backgroundColor: '#00c499',
                borderRadius: 8,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        maxRotation: 45
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endsection
