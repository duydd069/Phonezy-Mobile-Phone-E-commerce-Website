<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Xóa bảng nếu đã tồn tại (trường hợp bảng được tạo thủ công)
        Schema::dropIfExists('wishlists');
        
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            // Sử dụng bigInteger không unsigned để khớp với users.id và products.id
            $table->bigInteger('user_id');
            $table->bigInteger('product_id');
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            
            // Đảm bảo mỗi user chỉ có thể thêm 1 sản phẩm vào wishlist 1 lần
            $table->unique(['user_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wishlists');
    }
};
