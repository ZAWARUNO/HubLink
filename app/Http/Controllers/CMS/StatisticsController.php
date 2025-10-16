<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use App\Models\Order;
use App\Models\Component;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StatisticsController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil semua domain milik user
        $domains = $user->domains()->with(['components', 'orders', 'visitors'])->get();

        // Hitung total pengunjung (dari tabel visitors - unique session per hari)
        $totalVisitors = $domains->sum(function ($domain) {
            return $domain->visitors()->distinct('session_id')->count('session_id');
        });

        // Total pembelian
        $totalPurchases = $domains->sum(function ($domain) {
            return $domain->orders()->where('transaction_status', 'settlement')->count();
        });

        // Total pendapatan
        $totalRevenue = $domains->sum(function ($domain) {
            return $domain->orders()->where('transaction_status', 'settlement')->sum('amount');
        });

        // Total produk (template adalah produk yang dijual)
        $totalProducts = $domains->sum(function ($domain) {
            return $domain->components()->where('type', 'template')->count();
        });

        // Data untuk chart pendapatan per bulan (6 bulan terakhir)
        $revenueData = [];
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthRevenue = $domains->sum(function ($domain) use ($date) {
                return $domain->orders()
                    ->where('transaction_status', 'settlement')
                    ->whereMonth('paid_at', $date->month)
                    ->whereYear('paid_at', $date->year)
                    ->sum('amount');
            });

            $revenueData[] = $monthRevenue;
            $months[] = $date->format('M Y');
        }

        // Data untuk chart penjualan per produk (top 5)
        $topProducts = $domains->flatMap(function ($domain) {
            return $domain->orders()
                ->where('transaction_status', 'settlement')
                ->select('product_title', DB::raw('count(*) as sales_count'), DB::raw('sum(amount) as total_revenue'))
                ->groupBy('product_title')
                ->get();
        })->sortByDesc('sales_count')->take(5);

        // Data untuk chart status pembayaran
        $paymentStatusData = [];
        $paymentStatusLabels = [];
        $paymentStatusColors = ['#10B981', '#F59E0B', '#EF4444'];

        foreach (['settlement', 'pending', 'cancel'] as $status) {
            $count = $domains->sum(function ($domain) use ($status) {
                return $domain->orders()->where('transaction_status', $status)->count();
            });
            $paymentStatusData[] = $count;
            $paymentStatusLabels[] = ucfirst($status);
        }

        // Data untuk chart performa domain
        $domainPerformance = $domains->map(function ($domain) {
            $orders = $domain->orders()->where('transaction_status', 'settlement');
            return [
                'domain' => $domain->title ?? $domain->slug,
                'revenue' => $orders->sum('amount'),
                'orders' => $orders->count(),
                'products' => $domain->components()->where('type', 'template')->count()
            ];
        })->sortByDesc('revenue')->take(5);

        // Hitung persentase pertumbuhan bulan ini vs bulan lalu
        $currentMonth = Carbon::now();
        $lastMonth = Carbon::now()->subMonth();

        // Pertumbuhan pengunjung (dari tabel visitors)
        $currentMonthVisitors = $domains->sum(function ($domain) use ($currentMonth) {
            return $domain->visitors()
                ->whereMonth('visited_at', $currentMonth->month)
                ->whereYear('visited_at', $currentMonth->year)
                ->distinct('session_id')
                ->count('session_id');
        });
        $lastMonthVisitors = $domains->sum(function ($domain) use ($lastMonth) {
            return $domain->visitors()
                ->whereMonth('visited_at', $lastMonth->month)
                ->whereYear('visited_at', $lastMonth->year)
                ->distinct('session_id')
                ->count('session_id');
        });
        $visitorsGrowth = $lastMonthVisitors > 0 ? round((($currentMonthVisitors - $lastMonthVisitors) / $lastMonthVisitors) * 100, 1) : 0;

        // Pertumbuhan pembelian
        $currentMonthPurchases = $domains->sum(function ($domain) use ($currentMonth) {
            return $domain->orders()
                ->where('transaction_status', 'settlement')
                ->whereMonth('paid_at', $currentMonth->month)
                ->whereYear('paid_at', $currentMonth->year)
                ->count();
        });
        $lastMonthPurchases = $domains->sum(function ($domain) use ($lastMonth) {
            return $domain->orders()
                ->where('transaction_status', 'settlement')
                ->whereMonth('paid_at', $lastMonth->month)
                ->whereYear('paid_at', $lastMonth->year)
                ->count();
        });
        $purchasesGrowth = $lastMonthPurchases > 0 ? round((($currentMonthPurchases - $lastMonthPurchases) / $lastMonthPurchases) * 100, 1) : 0;

        // Pertumbuhan pendapatan
        $currentMonthRevenue = $domains->sum(function ($domain) use ($currentMonth) {
            return $domain->orders()
                ->where('transaction_status', 'settlement')
                ->whereMonth('paid_at', $currentMonth->month)
                ->whereYear('paid_at', $currentMonth->year)
                ->sum('amount');
        });
        $lastMonthRevenue = $domains->sum(function ($domain) use ($lastMonth) {
            return $domain->orders()
                ->where('transaction_status', 'settlement')
                ->whereMonth('paid_at', $lastMonth->month)
                ->whereYear('paid_at', $lastMonth->year)
                ->sum('amount');
        });
        $revenueGrowth = $lastMonthRevenue > 0 ? round((($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1) : 0;

        return view('cms.statistics', compact(
            'totalVisitors',
            'totalPurchases',
            'totalRevenue',
            'totalProducts',
            'revenueData',
            'months',
            'topProducts',
            'paymentStatusData',
            'paymentStatusLabels',
            'paymentStatusColors',
            'domainPerformance',
            'visitorsGrowth',
            'purchasesGrowth',
            'revenueGrowth'
        ));
    }

    /**
     * Export statistics to PDF
     */
    public function export()
    {
        $user = Auth::user();
        $domains = $user->domains()->with(['components', 'orders', 'visitors'])->get();

        // Ambil semua data statistik
        $totalVisitors = $domains->sum(function ($domain) {
            return $domain->visitors()->distinct('session_id')->count('session_id');
        });

        $totalPurchases = $domains->sum(function ($domain) {
            return $domain->orders()->where('transaction_status', 'settlement')->count();
        });

        $totalRevenue = $domains->sum(function ($domain) {
            return $domain->orders()->where('transaction_status', 'settlement')->sum('amount');
        });

        $totalProducts = $domains->sum(function ($domain) {
            return $domain->components()->where('type', 'template')->count();
        });

        // Data untuk export
        $data = [
            'user' => $user,
            'totalVisitors' => $totalVisitors,
            'totalPurchases' => $totalPurchases,
            'totalRevenue' => $totalRevenue,
            'totalProducts' => $totalProducts,
            'domains' => $domains,
            'exportDate' => Carbon::now()->format('d M Y H:i'),
        ];

        // Generate HTML untuk export
        $html = view('cms.statistics-export', $data)->render();

        // Return sebagai download HTML (bisa diubah ke PDF dengan library seperti DomPDF)
        return response($html)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'attachment; filename="statistik-' . date('Y-m-d') . '.html"');
    }
}
