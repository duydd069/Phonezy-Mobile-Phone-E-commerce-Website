<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RevenueReportController extends Controller
{
    public function index(Request $request)
    {
        // Lấy tham số từ request
        $period = $request->get('period', 'month'); // day, week, month, year, all
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        
        // Xử lý khoảng thời gian
        if ($startDate && $endDate) {
            $start = Carbon::parse($startDate)->startOfDay();
            $end = Carbon::parse($endDate)->endOfDay();
        } elseif ($period === 'all') {
            // Nếu chọn "tất cả", lấy từ ngày đầu tiên có đơn hàng
            $firstOrder = Order::orderBy('created_at', 'asc')->first();
            $start = $firstOrder ? Carbon::parse($firstOrder->created_at)->startOfDay() : Carbon::now()->subDays(30)->startOfDay();
            $end = Carbon::now()->endOfDay();
        } elseif ($period === 'day') {
            $end = Carbon::now()->endOfDay();
            $start = Carbon::now()->startOfDay();
        } elseif ($period === 'week') {
            $end = Carbon::now()->endOfDay();
            $start = Carbon::now()->subDays(7)->startOfDay();
        } elseif ($period === 'year') {
            $end = Carbon::now()->endOfDay();
            $start = Carbon::now()->startOfYear()->startOfDay();
        } else {
            // Mặc định: 30 ngày gần nhất
            $end = Carbon::now()->endOfDay();
            $start = Carbon::now()->subDays(30)->startOfDay();
        }

        // Tính tổng doanh thu
        $totalRevenue = (float) Order::where('status', '!=', 'cancelled')
            ->where('payment_status', 1) // 1 = đã thanh toán
            ->whereBetween('created_at', [$start, $end])
            ->sum('total') ?? 0;

        // Tính tổng số đơn hàng
        $totalOrders = (int) Order::where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [$start, $end])
            ->count();

        // Tính số đơn hàng đã thanh toán
        $paidOrders = (int) Order::where('status', '!=', 'cancelled')
            ->where('payment_status', 1) // 1 = đã thanh toán
            ->whereBetween('created_at', [$start, $end])
            ->count();

        // Tính tổng giảm giá
        $totalDiscount = (float) Order::where('status', '!=', 'cancelled')
            ->where('payment_status', 1) // 1 = đã thanh toán
            ->whereBetween('created_at', [$start, $end])
            ->sum('discount_amount') ?? 0;

        // Tính tổng phí vận chuyển
        $totalShipping = (float) Order::where('status', '!=', 'cancelled')
            ->where('payment_status', 1) // 1 = đã thanh toán
            ->whereBetween('created_at', [$start, $end])
            ->sum('shipping_fee') ?? 0;

        // Doanh thu theo ngày (cho biểu đồ)
        $dailyRevenue = $this->getDailyRevenue($start, $end);

        // Doanh thu theo sản phẩm
        $revenueByProduct = $this->getRevenueByProduct($start, $end);

        // Doanh thu theo danh mục
        $revenueByCategory = $this->getRevenueByCategory($start, $end);

        // Doanh thu theo trạng thái đơn hàng
        $revenueByStatus = $this->getRevenueByStatus($start, $end);

        // Thống kê theo tháng (12 tháng gần nhất)
        $monthlyStats = $this->getMonthlyStats();

        return view('admin.revenue.index', compact(
            'totalRevenue',
            'totalOrders',
            'paidOrders',
            'totalDiscount',
            'totalShipping',
            'dailyRevenue',
            'revenueByProduct',
            'revenueByCategory',
            'revenueByStatus',
            'monthlyStats',
            'start',
            'end',
            'period'
        ));
    }

    /**
     * Lấy doanh thu theo ngày
     */
    private function getDailyRevenue($start, $end)
    {
        return Order::where('status', '!=', 'cancelled')
            ->where('payment_status', 1) // 1 = đã thanh toán
            ->whereBetween('created_at', [$start, $end])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => Carbon::parse($item->date)->format('d/m/Y'),
                    'revenue' => (float) $item->revenue,
                    'orders' => (int) $item->orders,
                ];
            });
    }

    /**
     * Lấy doanh thu theo sản phẩm
     */
    private function getRevenueByProduct($start, $end, $limit = 10)
    {
        return OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', '!=', 'cancelled')
            ->where('orders.payment_status', 1) // 1 = đã thanh toán
            ->whereBetween('orders.created_at', [$start, $end])
            ->select(
                'order_items.product_name',
                DB::raw('SUM(order_items.total_price) as revenue'),
                DB::raw('SUM(order_items.quantity) as quantity_sold')
            )
            ->groupBy('order_items.product_name')
            ->orderByDesc('revenue')
            ->limit($limit)
            ->get();
    }

    /**
     * Lấy doanh thu theo danh mục
     */
    private function getRevenueByCategory($start, $end)
    {
        return OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('orders.status', '!=', 'cancelled')
            ->where('orders.payment_status', 1) // 1 = đã thanh toán
            ->whereBetween('orders.created_at', [$start, $end])
            ->select(
                'categories.name as category_name',
                DB::raw('SUM(order_items.total_price) as revenue'),
                DB::raw('SUM(order_items.quantity) as quantity_sold')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('revenue')
            ->get();
    }

    /**
     * Lấy doanh thu theo trạng thái đơn hàng
     */
    private function getRevenueByStatus($start, $end)
    {
        return Order::whereBetween('created_at', [$start, $end])
            ->select(
                'status',
                DB::raw('SUM(total) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->groupBy('status')
            ->get();
    }

    /**
     * Lấy thống kê theo tháng (12 tháng gần nhất)
     */
    private function getMonthlyStats()
    {
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = [
                'month' => $date->format('m/Y'),
                'month_name' => $date->format('M Y'),
                'start' => $date->copy()->startOfMonth(),
                'end' => $date->copy()->endOfMonth(),
            ];
        }

        $stats = [];
        foreach ($months as $month) {
            $revenue = Order::where('status', '!=', 'cancelled')
                ->where('payment_status', 1) // 1 = đã thanh toán
                ->whereBetween('created_at', [$month['start'], $month['end']])
                ->sum('total');

            $orders = Order::where('status', '!=', 'cancelled')
                ->whereBetween('created_at', [$month['start'], $month['end']])
                ->count();

            $stats[] = [
                'month' => $month['month'],
                'month_name' => $month['month_name'],
                'revenue' => (float) $revenue,
                'orders' => (int) $orders,
            ];
        }

        return $stats;
    }

    /**
     * Export báo cáo doanh thu (JSON cho AJAX)
     */
    public function export(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        
        if ($startDate && $endDate) {
            $start = Carbon::parse($startDate)->startOfDay();
            $end = Carbon::parse($endDate)->endOfDay();
        } else {
            $end = Carbon::now()->endOfDay();
            $start = Carbon::now()->subDays(30)->startOfDay();
        }

        $orders = Order::with(['user', 'items'])
            ->where('status', '!=', 'cancelled')
            ->where('payment_status', 1) // 1 = đã thanh toán
            ->whereBetween('created_at', [$start, $end])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $orders,
        ]);
    }
}

