<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderReturn;
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

        // Tính tổng doanh thu (trừ đi phần hoàn hàng)
        $totalRevenue = (float) Order::where('status', '!=', 'cancelled')
            ->where('status', '!=', 'da_hoan_tien') // Trừ đơn hàng đã hoàn tiền
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

        // Thống kê đơn hàng hoàn trả
        $returnStats = $this->getReturnStatistics($start, $end);

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
            'returnStats',
            'start',
            'end',
            'period'
        ));
    }

    /**
     * Lấy doanh thu theo ngày (bao gồm thống kê hoàn trả)
     */
    private function getDailyRevenue($start, $end)
    {
        // Lấy doanh thu và số đơn hàng theo ngày
        $revenueData = Order::where('status', '!=', 'cancelled')
            ->where('status', '!=', 'da_hoan_tien') // Trừ đơn hàng đã hoàn tiền
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
            ->keyBy(function ($item) {
                return Carbon::parse($item->date)->format('Y-m-d');
            });

        // Lấy thống kê hoàn trả theo ngày (chỉ lấy các đơn đã hoàn tiền và có refunded_at)
        $returnData = OrderReturn::join('orders', 'order_returns.order_id', '=', 'orders.id')
            ->where('order_returns.status', 'Đã hoàn tiền')
            ->whereNotNull('order_returns.refunded_at')
            ->whereBetween('order_returns.refunded_at', [$start, $end])
            ->select(
                DB::raw('DATE(order_returns.refunded_at) as date'),
                DB::raw('SUM(orders.total) as refunded_amount'),
                DB::raw('COUNT(*) as refunded_count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy(function ($item) {
                return Carbon::parse($item->date)->format('Y-m-d');
            });

        // Tạo danh sách tất cả các ngày trong khoảng thời gian
        $allDates = [];
        $currentDate = Carbon::parse($start);
        $endDate = Carbon::parse($end);

        while ($currentDate <= $endDate) {
            $dateKey = $currentDate->format('Y-m-d');
            $allDates[$dateKey] = [
                'date' => $currentDate->format('d/m/Y'),
                'revenue' => 0,
                'orders' => 0,
                'refunded_amount' => 0,
                'refunded_count' => 0,
            ];
            $currentDate->addDay();
        }

        // Merge dữ liệu doanh thu
        foreach ($revenueData as $dateKey => $item) {
            if (isset($allDates[$dateKey])) {
                $allDates[$dateKey]['revenue'] = (float) $item->revenue;
                $allDates[$dateKey]['orders'] = (int) $item->orders;
            }
        }

        // Merge dữ liệu hoàn trả
        foreach ($returnData as $dateKey => $item) {
            if (isset($allDates[$dateKey])) {
                $allDates[$dateKey]['refunded_amount'] = (float) $item->refunded_amount;
                $allDates[$dateKey]['refunded_count'] = (int) $item->refunded_count;
            }
        }

        return array_values($allDates);
    }

    /**
     * Lấy doanh thu theo sản phẩm
     */
    private function getRevenueByProduct($start, $end, $limit = 10)
    {
        return OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', '!=', 'cancelled')
            ->where('orders.status', '!=', 'da_hoan_tien') // Trừ đơn hàng đã hoàn tiền
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
            ->where('orders.status', '!=', 'da_hoan_tien') // Trừ đơn hàng đã hoàn tiền
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
                ->where('status', '!=', 'da_hoan_tien') // Trừ đơn hàng đã hoàn tiền
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
     * Lấy thống kê đơn hàng hoàn trả
     */
    private function getReturnStatistics($start, $end)
    {
        // Tổng số yêu cầu hoàn trả
        $totalReturns = OrderReturn::whereBetween('created_at', [$start, $end])
            ->count();

        // Số yêu cầu đã hoàn tiền
        $refundedReturns = OrderReturn::where('status', 'Đã hoàn tiền')
            ->whereBetween('created_at', [$start, $end])
            ->count();

        // Tổng giá trị đơn hàng đã hoàn trả
        $totalRefundedAmount = OrderReturn::join('orders', 'order_returns.order_id', '=', 'orders.id')
            ->where('order_returns.status', 'Đã hoàn tiền')
            ->whereBetween('order_returns.created_at', [$start, $end])
            ->sum('orders.total') ?? 0;

        // Số yêu cầu đang chờ xử lý
        $pendingReturns = OrderReturn::whereIn('status', ['Chưa giải quyết', 'Thông qua', 'Đang vận chuyển', 'Đã nhận'])
            ->whereBetween('created_at', [$start, $end])
            ->count();

        // Chi tiết đơn hàng hoàn trả
        $returnDetails = OrderReturn::with(['order', 'order.user'])
            ->whereBetween('created_at', [$start, $end])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get()
            ->map(function ($return) {
                return [
                    'id' => $return->id,
                    'return_code' => $return->return_code,
                    'order_id' => $return->order_id,
                    'order_total' => $return->order->total ?? 0,
                    'status' => $return->status,
                    'refunded_at' => $return->refunded_at?->format('d/m/Y H:i'),
                    'created_at' => $return->created_at->format('d/m/Y H:i'),
                ];
            });

        return [
            'total_returns' => $totalReturns,
            'refunded_returns' => $refundedReturns,
            'total_refunded_amount' => (float) $totalRefundedAmount,
            'pending_returns' => $pendingReturns,
            'return_details' => $returnDetails,
        ];
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
            ->where('status', '!=', 'da_hoan_tien') // Trừ đơn hàng đã hoàn tiền
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

