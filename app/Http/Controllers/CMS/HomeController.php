<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Models\Visitor;
use App\Models\Order;
use App\Models\Component;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $domains = $user->domains;
        
        // Ambil domain pertama untuk backward compatibility
        $domain = $domains->first();

        // Hitung statistik
        $totalVisitors = $domains->sum(function ($domain) {
            return $domain->visitors()->distinct('session_id')->count('session_id');
        });

        $totalOrders = $domains->sum(function ($domain) {
            return $domain->orders()->where('transaction_status', 'settlement')->count();
        });

        $totalRevenue = $domains->sum(function ($domain) {
            return $domain->orders()->where('transaction_status', 'settlement')->sum('amount');
        });

        $totalProducts = $domains->sum(function ($domain) {
            return $domain->components()->where('type', 'template')->count();
        });

        // Statistik bulan ini
        $currentMonth = Carbon::now();
        $visitorsThisMonth = $domains->sum(function ($domain) use ($currentMonth) {
            return $domain->visitors()
                ->whereMonth('visited_at', $currentMonth->month)
                ->whereYear('visited_at', $currentMonth->year)
                ->distinct('session_id')
                ->count('session_id');
        });

        $ordersThisMonth = $domains->sum(function ($domain) use ($currentMonth) {
            return $domain->orders()
                ->where('transaction_status', 'settlement')
                ->whereMonth('paid_at', $currentMonth->month)
                ->whereYear('paid_at', $currentMonth->year)
                ->count();
        });

        $revenueThisMonth = $domains->sum(function ($domain) use ($currentMonth) {
            return $domain->orders()
                ->where('transaction_status', 'settlement')
                ->whereMonth('paid_at', $currentMonth->month)
                ->whereYear('paid_at', $currentMonth->year)
                ->sum('amount');
        });

        // Hitung conversion rate (orders / visitors * 100)
        $conversionRate = $totalVisitors > 0 ? round(($totalOrders / $totalVisitors) * 100, 1) : 0;

        // Recent orders (5 terakhir)
        $recentOrders = Order::whereIn('domain_id', $domains->pluck('id'))
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('cms.home', compact(
            'user',
            'domain',
            'domains',
            'totalVisitors',
            'totalOrders',
            'totalRevenue',
            'totalProducts',
            'visitorsThisMonth',
            'ordersThisMonth',
            'revenueThisMonth',
            'conversionRate',
            'recentOrders'
        ));
    }
}
