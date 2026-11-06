<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->startOfDay();

        $todayOrders = Order::where('created_at', '>=', $today);
        $todaySales = (float) $todayOrders->sum('total_amount') - (float) \App\Models\OrderReturn::where('created_at', '>=', $today)->sum('refund_amount');
        $todayCount = $todayOrders->count();
        $yesterdaySales = (float) Order::whereBetween('created_at', [$today->copy()->subDay(), $today])->sum('total_amount');

        $trend = collect(range(6, 0))->map(function ($daysAgo) {
            $day = now()->subDays($daysAgo)->startOfDay();
            return [
                'label' => $day->format('D'),
                'total' => (float) Order::whereBetween('created_at', [$day, $day->copy()->endOfDay()])->sum('total_amount'),
            ];
        });

        // Top sellers over the last 30 days, aggregated from each invoice's cart.
        $sold = [];
        Order::where('created_at', '>=', now()->subDays(30))->get(['cart_items'])->each(function ($order) use (&$sold) {
            foreach ($order->cart_items ?? [] as $item) {
                $name = $item['name'] ?? 'Unknown';
                $sold[$name] = ($sold[$name] ?? 0) + (int) ($item['qty'] ?? 0);
            }
        });
        arsort($sold);

        // Reorder suggestions: units sold per day over 30 days vs. what is on hand.
        $reorder = Medicine::whereIn('name', array_keys($sold))->get()->map(function ($m) use ($sold) {
            $perDay = $sold[$m->name] / 30;
            $daysLeft = $perDay > 0 ? $m->stock / $perDay : INF;
            return ['id' => $m->id, 'name' => $m->name, 'stock' => $m->stock, 'days_left' => (int) floor($daysLeft),
                    'order_qty' => max(0, (int) ceil($perDay * 30 - $m->stock))];
        })->filter(fn ($r) => $r['days_left'] < 14 && $r['order_qty'] > 0)->sortBy('days_left')->take(6)->values();

        return view('dashboard', [
            'todaySales' => $todaySales,
            'todayCount' => $todayCount,
            'salesChange' => $yesterdaySales > 0 ? round((($todaySales - $yesterdaySales) / $yesterdaySales) * 100) : null,
            'trend' => $trend,
            'reorder' => $reorder,
            'topSellers' => array_slice($sold, 0, 5, true),
            'totalMedicines' => Medicine::count(),
            'stockValue' => (float) Medicine::selectRaw('COALESCE(SUM(price * stock), 0) as v')->value('v'),
            'outOfStock' => Medicine::where('stock', 0)->count(),
            'lowStock' => Medicine::where('stock', '>', 0)->where('stock', '<', 10)->count(),
            'expired' => Medicine::where('expiry_date', '<', now())->count(),
            'expiringSoon' => Medicine::whereBetween('expiry_date', [now(), now()->addMonths(3)])->count(),
            'recentOrders' => Order::latest()->take(6)->get(),
        ]);
    }
}
