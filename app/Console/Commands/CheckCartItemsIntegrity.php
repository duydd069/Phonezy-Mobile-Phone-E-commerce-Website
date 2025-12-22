<?php

namespace App\Console\Commands;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Console\Command;

class CheckCartItemsIntegrity extends Command
{
    protected $signature = 'cart:check-integrity';
    protected $description = 'Kiểm tra tính toàn vẹn của cart items và order items';

    public function handle()
    {
        $this->info('=== KIỂM TRA TÍNH TOÀN VẸN DỮ LIỆU ===');
        $this->newLine();

        // 1. Kiểm tra cart items orphan (không thuộc về cart nào)
        $this->info('1. Kiểm tra cart items orphan...');
        $orphanItems = CartItem::whereDoesntHave('cart')->get();
        if ($orphanItems->count() > 0) {
            $this->warn("   Tìm thấy {$orphanItems->count()} cart items orphan:");
            foreach ($orphanItems as $item) {
                $this->line("   - CartItem ID: {$item->id}, Cart ID: {$item->cart_id}, Variant ID: {$item->product_variant_id}");
            }
        } else {
            $this->info('   ✓ Không có cart items orphan');
        }
        $this->newLine();

        // 2. Kiểm tra cart items thuộc về cart đã converted
        $this->info('2. Kiểm tra cart items trong cart đã converted...');
        $itemsInConvertedCarts = CartItem::whereHas('cart', function ($query) {
            $query->where('status', 'converted');
        })->get();
        if ($itemsInConvertedCarts->count() > 0) {
            $this->warn("   Tìm thấy {$itemsInConvertedCarts->count()} cart items trong cart đã converted:");
            foreach ($itemsInConvertedCarts->take(10) as $item) {
                $this->line("   - CartItem ID: {$item->id}, Cart ID: {$item->cart_id} (status: {$item->cart->status})");
            }
            if ($itemsInConvertedCarts->count() > 10) {
                $this->line("   ... và " . ($itemsInConvertedCarts->count() - 10) . " items khác");
            }
        } else {
            $this->info('   ✓ Không có cart items trong cart đã converted');
        }
        $this->newLine();

        // 3. Kiểm tra orders có duplicate items
        $this->info('3. Kiểm tra orders có duplicate items...');
        $orders = Order::with('items')->get();
        $duplicateOrders = [];
        foreach ($orders as $order) {
            $itemsByProduct = $order->items->groupBy('product_id');
            foreach ($itemsByProduct as $productId => $items) {
                if ($items->count() > 1) {
                    $duplicateOrders[] = [
                        'order_id' => $order->id,
                        'cart_id' => $order->cart_id,
                        'user_id' => $order->user_id,
                        'product_id' => $productId,
                        'product_name' => $items->first()->product_name,
                        'duplicate_count' => $items->count(),
                    ];
                }
            }
        }
        if (count($duplicateOrders) > 0) {
            $this->warn("   Tìm thấy " . count($duplicateOrders) . " orders có duplicate items:");
            foreach ($duplicateOrders as $dup) {
                $this->line("   - Order ID: {$dup['order_id']}, Cart ID: {$dup['cart_id']}, User ID: {$dup['user_id']}");
                $this->line("     Product: {$dup['product_name']} (ID: {$dup['product_id']}) - {$dup['duplicate_count']} items");
            }
        } else {
            $this->info('   ✓ Không có orders có duplicate items');
        }
        $this->newLine();

        // 4. Kiểm tra cart items không có variant
        $this->info('4. Kiểm tra cart items không có variant...');
        $itemsWithoutVariant = CartItem::whereDoesntHave('variant')->get();
        if ($itemsWithoutVariant->count() > 0) {
            $this->warn("   Tìm thấy {$itemsWithoutVariant->count()} cart items không có variant:");
            foreach ($itemsWithoutVariant as $item) {
                $this->line("   - CartItem ID: {$item->id}, Cart ID: {$item->cart_id}, Variant ID: {$item->product_variant_id}");
            }
        } else {
            $this->info('   ✓ Tất cả cart items đều có variant');
        }
        $this->newLine();

        // 5. Kiểm tra multiple active carts cho cùng user
        $this->info('5. Kiểm tra multiple active carts cho cùng user...');
        $usersWithMultipleCarts = Cart::where('status', 'active')
            ->selectRaw('user_id, COUNT(*) as count')
            ->groupBy('user_id')
            ->having('count', '>', 1)
            ->get();
        if ($usersWithMultipleCarts->count() > 0) {
            $this->warn("   Tìm thấy users có nhiều active carts:");
            foreach ($usersWithMultipleCarts as $userCart) {
                $carts = Cart::where('user_id', $userCart->user_id)
                    ->where('status', 'active')
                    ->orderBy('updated_at', 'desc')
                    ->get();
                $this->line("   - User ID: {$userCart->user_id} có {$userCart->count} active carts:");
                foreach ($carts as $cart) {
                    $itemsCount = $cart->items()->count();
                    $this->line("     Cart ID: {$cart->id}, Updated: {$cart->updated_at}, Items: {$itemsCount}");
                }
            }
        } else {
            $this->info('   ✓ Không có users có nhiều active carts');
        }
        $this->newLine();

        // 6. Tổng kết
        $this->info('=== TỔNG KẾT ===');
        $this->info("Total carts: " . Cart::count());
        $this->info("Active carts: " . Cart::where('status', 'active')->count());
        $this->info("Total cart items: " . CartItem::count());
        $this->info("Total orders: " . Order::count());
        $this->info("Total order items: " . OrderItem::count());

        return 0;
    }
}

