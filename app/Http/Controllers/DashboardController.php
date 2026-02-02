<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    protected function scopeBranch($query, $column = 'branch_id')
    {
        $user = auth()->user();

        if (!$user->is_superadmin) {
            $query->where($column, $user->branch_id);
        }

        return $query;
    }

    public function index()
    {
        $user = auth()->user();

        // ORDERS
        $ordersToday = $this->scopeBranch(
            DB::table('orders')
        )->whereDate('created_at', today())->count();

        $ordersYesterday = $this->scopeBranch(
            DB::table('orders')
        )->whereDate('created_at', today()->subDay())->count();

        // REVENUE
        $revenueToday = $this->scopeBranch(
            DB::table('orders')
        )->whereDate('created_at', today())
            ->where('payment_status', 'paid')
            ->sum('total_amount');

        // ACTIVE CUSTOMERS (24H)
        $activeCustomers = $this->scopeBranch(
            DB::table('orders')
        )->where('created_at', '>=', now()->subDay())
            ->distinct('customer_id')
            ->count('customer_id');

        // ORDER STATUS (30 DAYS)
        $orderStats = $this->scopeBranch(
            DB::table('orders')
        )->selectRaw("
            SUM(CASE WHEN status = 'complete' THEN 1 ELSE 0 END) as complete,
            SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled,
            SUM(CASE WHEN status = 'return' THEN 1 ELSE 0 END) as returned
        ")->whereBetween('created_at', [now()->subDays(30), now()])
            ->first();

        // ORDERS PER DAY (7 DAYS)
        $ordersPerDay = $this->scopeBranch(
            DB::table('orders')
        )->selectRaw("DATE(created_at) as date, COUNT(*) as total")
            ->whereBetween('created_at', [now()->subDays(6), now()])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // RECENT ORDERS
        $recentOrders = $this->scopeBranch(
            DB::table('orders')
        )->join('customers', 'customers.id', '=', 'orders.customer_id')
            ->select(
                'orders.order_no',
                'customers.full_name',
                'orders.total_amount',
                'orders.status',
                'orders.created_at'
            )
            ->latest('orders.created_at')
            ->limit(10)
            ->get();

        return view('dashboard.dashboard', compact(
            'ordersToday',
            'ordersYesterday',
            'revenueToday',
            'activeCustomers',
            'orderStats',
            'ordersPerDay',
            'recentOrders'
        ));
    }
}
