<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of orders
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $domains = $user->domains;

        // Query orders
        $query = Order::whereIn('domain_id', $domains->pluck('id'))
            ->with('domain');

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('transaction_status', $request->status);
        }

        // Filter by domain
        if ($request->has('domain_id') && $request->domain_id != '') {
            $query->where('domain_id', $request->domain_id);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_id', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('product_title', 'like', "%{$search}%");
            });
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Paginate
        $orders = $query->paginate(20)->withQueryString();

        // Statistics
        $totalOrders = Order::whereIn('domain_id', $domains->pluck('id'))->count();
        $settlementOrders = Order::whereIn('domain_id', $domains->pluck('id'))
            ->where('transaction_status', 'settlement')
            ->count();
        $pendingOrders = Order::whereIn('domain_id', $domains->pluck('id'))
            ->where('transaction_status', 'pending')
            ->count();
        $totalRevenue = Order::whereIn('domain_id', $domains->pluck('id'))
            ->where('transaction_status', 'settlement')
            ->sum('amount');

        return view('cms.orders.index', compact(
            'orders',
            'domains',
            'totalOrders',
            'settlementOrders',
            'pendingOrders',
            'totalRevenue'
        ));
    }

    /**
     * Display the specified order
     */
    public function show($id)
    {
        $user = Auth::user();
        $order = Order::whereIn('domain_id', $user->domains->pluck('id'))
            ->with('domain')
            ->findOrFail($id);

        return view('cms.orders.show', compact('order'));
    }

    /**
     * Delete order
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $order = Order::whereIn('domain_id', $user->domains->pluck('id'))
            ->findOrFail($id);

        try {
            $order->delete();
            return redirect()->route('cms.orders.index')
                ->with('success', 'Order berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus order: ' . $e->getMessage());
        }
    }
}
