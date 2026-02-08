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
        // angka ringkasan
        $ordersToday = 0;
        $ordersYesterday = 0;
        $revenueToday = 0;
        $activeCustomers = 0;

        // object statistik order
        $orderStats = (object) [
            'complete'  => 0,
            'cancelled' => 0,
            'returned'  => 0,
        ];

        // collection untuk chart
        $ordersPerDay = collect([]);

        // tabel recent orders
        $recentOrders = collect([]);

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
