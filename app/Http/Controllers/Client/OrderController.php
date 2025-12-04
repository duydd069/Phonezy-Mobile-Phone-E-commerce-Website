<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('client.login')->with('error', 'Please login to view your orders.');
        }

        $query = Order::where('user_id', $user->id)
            ->with(['items', 'coupon'])
            ->orderByDesc('created_at');

        // Lọc theo trạng thái
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('electro.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $user = auth()->user();

        // Check order access permission
        if (!$user || (int) $order->user_id !== (int) $user->id) {
            abort(403, 'You do not have permission to view this order.');
        }

        $order->load(['items', 'user', 'coupon']);
        $paymentMethods = config('checkout.payment_methods', []);

        return view('electro.orders.show', compact('order', 'paymentMethods'));
    }
}
