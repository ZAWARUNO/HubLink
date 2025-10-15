<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use App\Models\Order;
use App\Models\Component;
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
        $domains = $user->domains()->with(['components', 'orders'])->get();

        // Hitung total pengunjung (simulasi berdasarkan jumlah order untuk sekarang)
        $totalVisitors = $domains->sum(function ($domain) {
            return $domain->orders()->count() * rand(3, 8); // Simulasi pengunjung
        });

        // Total pembelian
        $totalPurchases = $domains->sum(function ($domain) {
            return $domain->orders()->where('transaction_status', 'settlement')->count();
        });

        // Total pendapatan
        $totalRevenue = $domains->sum(function ($domain) {
            return $domain->orders()->where('transaction_status', 'settlement')->sum('amount');
        });

        // Total produk
        $totalProducts = $domains->sum(function ($domain) {
            return $domain->components()->where('type', 'product')->count();
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
                'domain' => $domain->title,
                'revenue' => $orders->sum('amount'),
                'orders' => $orders->count(),
                'products' => $domain->components()->where('type', 'product')->count()
            ];
        })->sortByDesc('revenue')->take(5);

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
            'domainPerformance'
        ));
    }
}
