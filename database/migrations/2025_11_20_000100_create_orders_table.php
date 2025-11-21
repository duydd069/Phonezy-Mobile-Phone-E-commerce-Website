<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained('carts');
            $table->foreignId('user_id')->constrained('users');
            $table->decimal('subtotal', 12, 2);
            $table->decimal('shipping_fee', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('total', 12, 2);
            $table->string('payment_method', 50);
            $table->string('payment_status', 50)->default('pending');
            $table->string('status', 50)->default('pending');
            $table->string('shipping_full_name', 120);
            $table->string('shipping_email', 150)->nullable();
            $table->string('shipping_phone', 30);
            $table->string('shipping_city', 120)->nullable();
            $table->string('shipping_district', 120)->nullable();
            $table->string('shipping_ward', 120)->nullable();
            $table->text('shipping_address');
            $table->text('notes')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};


