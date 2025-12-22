<?php

use App\Models\User;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

// Tìm user quanm9677
$user = User::where('name', 'quanm9677')
            ->orWhere('email', 'quanm9677@gmail.com')
            ->first();

if (!$user) {
    echo "User 'quanm9677' not found.\n";
    exit;
}

echo "User found: ID {$user->id}, Name: {$user->name}, Email: {$user->email}\n";
echo "---------------------------------------------------\n";

// Kiểm tra tất cả các carts của user
$carts = Cart::where('user_id', $user->id)
    ->with('items.variant.product', 'items.product')
    ->orderBy('created_at', 'desc')
    ->get();

echo "Found " . $carts->count() . " carts for this user.\n";

foreach ($carts as $cart) {
    echo "\n[Cart ID: {$cart->id}] Status: {$cart->status}, Created: {$cart->created_at}, Updated: {$cart->updated_at}\n";
    
    // Check if this cart has an order
    $order = Order::where('cart_id', $cart->id)->first();
    if ($order) {
        echo "  -> Linked Order ID: {$order->id}, Total: " . number_format($order->total) . "\n";
    } else {
        echo "  -> No linked order.\n";
    }

    if ($cart->items->isEmpty()) {
        echo "  -> Cart is empty.\n";
    } else {
        foreach ($cart->items as $item) {
            $productName = $item->variant->product->name ?? $item->product->name ?? 'Unknown Product';
            $variantName = $item->variant->name ?? 'No Variant';
            echo "  - Item ID: {$item->id}, Product: {$productName} ({$variantName}), Qty: {$item->quantity}, Price: " . number_format($item->price) . "\n";
        }
    }
}

echo "\n---------------------------------------------------\n";
echo "Checking for any carts with ID 1 (Admin) that might have issues...\n";
$adminCarts = Cart::where('user_id', 1)->with('items')->get();
foreach ($adminCarts as $cart) {
     if ($cart->items->isNotEmpty()) {
         echo "[Admin Cart ID: {$cart->id}] Status: {$cart->status}\n";
         foreach ($cart->items as $item) {
             echo "  - Item: " . ($item->variant->product->name ?? 'Unknown') . ", Qty: {$item->quantity}\n";
         }
     }
}
