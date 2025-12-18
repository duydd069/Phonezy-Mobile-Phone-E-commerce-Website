<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            // Lưu snapshot giá khi thêm vào giỏ hàng (tránh giá bị thay đổi sau đó)
            if (!Schema::hasColumn('cart_items', 'price_at_time')) {
                $table->decimal('price_at_time', 12, 2)->nullable()->after('quantity')->comment('Giá bán tại thời điểm thêm vào giỏ');
            }
            if (!Schema::hasColumn('cart_items', 'price_sale_at_time')) {
                $table->decimal('price_sale_at_time', 12, 2)->nullable()->after('price_at_time')->comment('Giá khuyến mãi tại thời điểm thêm vào giỏ');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            if (Schema::hasColumn('cart_items', 'price_sale_at_time')) {
                $table->dropColumn('price_sale_at_time');
            }
            if (Schema::hasColumn('cart_items', 'price_at_time')) {
                $table->dropColumn('price_at_time');
            }
        });
    }
};