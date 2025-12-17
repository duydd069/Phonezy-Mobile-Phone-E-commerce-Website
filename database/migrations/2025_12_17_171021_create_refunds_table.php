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
        Schema::dropIfExists('refunds'); // Xóa bảng nếu đã tồn tại
        
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->decimal('amount', 15, 2); // Số tiền hoàn
            $table->string('transaction_no')->nullable(); // Mã giao dịch hoàn tiền từ VNPAY
            $table->string('vnpay_response_code')->nullable(); // Mã response từ VNPAY
            $table->text('vnpay_response_data')->nullable(); // Toàn bộ response từ VNPAY (JSON)
            $table->unsignedBigInteger('refunded_by')->nullable(); // Người thực hiện hoàn tiền (user_id)
            $table->text('reason')->nullable(); // Lý do hoàn tiền
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending'); // Trạng thái hoàn tiền
            $table->timestamp('refunded_at')->nullable(); // Thời gian hoàn tiền thành công
            $table->timestamps();
            
            $table->index('order_id');
            $table->index('transaction_no');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refunds');
    }
};
