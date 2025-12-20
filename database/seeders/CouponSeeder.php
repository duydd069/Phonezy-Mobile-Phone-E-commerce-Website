<?php

namespace Database\Seeders;

use App\Models\Coupon;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy một số product và user để gán cho coupon (nếu cần)
        $products = Product::limit(5)->get();
        $users = User::limit(3)->get();

        // 1. Coupon order-level, public, percent với max_discount
        $coupon1 = Coupon::create([
            'code' => 'GIAM20',
            'type' => 'public',
            'promotion_type' => 'order',
            'discount_type' => 'percent',
            'discount_value' => 20, // 20%
            'min_order_value' => 500000, // Đơn tối thiểu 500k
            'max_discount' => 200000, // Giảm tối đa 200k
            'usage_limit' => 100, // 100 lượt sử dụng
            'usage_per_user' => 2, // Mỗi user dùng tối đa 2 lần
            'starts_at' => now(),
            'expires_at' => now()->addMonths(3),
            'used_count' => 0,
        ]);

        // 2. Coupon order-level, public, fixed với min_order_value
        $coupon2 = Coupon::create([
            'code' => 'GIAM50K',
            'type' => 'public',
            'promotion_type' => 'order',
            'discount_type' => 'fixed',
            'discount_value' => 50000, // Giảm 50k
            'min_order_value' => 300000, // Đơn tối thiểu 300k
            'usage_limit' => 50, // 50 lượt sử dụng
            'usage_per_user' => 1, // Mỗi user dùng 1 lần
            'starts_at' => now(),
            'expires_at' => now()->addMonths(2),
            'used_count' => 5, // Đã dùng 5 lần (để test)
        ]);

        // 3. Coupon product-level, public, percent
        if ($products->count() > 0) {
            $coupon3 = Coupon::create([
                'code' => 'GIAM10SP',
                'type' => 'public',
                'promotion_type' => 'product',
                'discount_type' => 'percent',
                'discount_value' => 10, // 10%
                'min_order_value' => null,
                'max_discount' => 50000, // Giảm tối đa 50k
                'usage_limit' => 200,
                'usage_per_user' => 3,
                'starts_at' => now(),
                'expires_at' => now()->addMonths(1),
                'used_count' => 0,
            ]);
            // Gán sản phẩm cho coupon này (lấy 3 sản phẩm đầu tiên)
            $coupon3->products()->sync($products->take(3)->pluck('id')->toArray());
        }

        // 4. Coupon product-level, public, fixed
        if ($products->count() > 0) {
            $coupon4 = Coupon::create([
                'code' => 'GIAM30KSP',
                'type' => 'public',
                'promotion_type' => 'product',
                'discount_type' => 'fixed',
                'discount_value' => 30000, // Giảm 30k mỗi sản phẩm
                'min_order_value' => 200000, // Đơn tối thiểu 200k
                'usage_limit' => 100,
                'usage_per_user' => 2,
                'starts_at' => now(),
                'expires_at' => now()->addMonths(1),
                'used_count' => 0,
            ]);
            // Gán sản phẩm cho coupon này (lấy 2 sản phẩm đầu tiên)
            $coupon4->products()->sync($products->take(2)->pluck('id')->toArray());
        }

        // 5. Coupon private, order-level, percent (chỉ dành cho một số user)
        if ($users->count() > 0) {
            $coupon5 = Coupon::create([
                'code' => 'VIP50',
                'type' => 'private',
                'promotion_type' => 'order',
                'discount_type' => 'percent',
                'discount_value' => 50, // 50%
                'min_order_value' => 1000000, // Đơn tối thiểu 1 triệu
                'max_discount' => 500000, // Giảm tối đa 500k
                'usage_limit' => 20, // Chỉ 20 lượt
                'usage_per_user' => 1, // Mỗi user 1 lần
                'starts_at' => now(),
                'expires_at' => now()->addMonths(6),
                'used_count' => 0,
            ]);
            // Gán users cho coupon này
            $coupon5->users()->sync($users->pluck('id')->toArray());
        }

        // 6. Coupon không giới hạn sử dụng
        $coupon6 = Coupon::create([
            'code' => 'KHONGHAN',
            'type' => 'public',
            'promotion_type' => 'order',
            'discount_type' => 'percent',
            'discount_value' => 5, // 5%
            'min_order_value' => null,
            'max_discount' => 50000, // Giảm tối đa 50k
            'usage_limit' => null, // Không giới hạn
            'usage_per_user' => null, // Không giới hạn mỗi user
            'starts_at' => now(),
            'expires_at' => null, // Không hết hạn
            'used_count' => 0,
        ]);

        // 7. Coupon đã hết hạn (để test validation)
        $coupon7 = Coupon::create([
            'code' => 'HETHAN',
            'type' => 'public',
            'promotion_type' => 'order',
            'discount_type' => 'fixed',
            'discount_value' => 100000,
            'min_order_value' => null,
            'usage_limit' => 10,
            'usage_per_user' => 1,
            'starts_at' => now()->subMonths(2),
            'expires_at' => now()->subDays(1), // Hết hạn hôm qua
            'used_count' => 0,
        ]);

        // 8. Coupon chưa đến thời gian sử dụng
        $coupon8 = Coupon::create([
            'code' => 'CHUABATDAU',
            'type' => 'public',
            'promotion_type' => 'order',
            'discount_type' => 'percent',
            'discount_value' => 15,
            'min_order_value' => null,
            'max_discount' => 100000,
            'usage_limit' => 50,
            'usage_per_user' => 1,
            'starts_at' => now()->addDays(7), // Bắt đầu sau 7 ngày
            'expires_at' => now()->addMonths(2),
            'used_count' => 0,
        ]);

        // 9. Coupon đã hết lượt sử dụng
        $coupon9 = Coupon::create([
            'code' => 'HETLUOT',
            'type' => 'public',
            'promotion_type' => 'order',
            'discount_type' => 'fixed',
            'discount_value' => 25000,
            'min_order_value' => 100000,
            'usage_limit' => 10,
            'usage_per_user' => 1,
            'starts_at' => now(),
            'expires_at' => now()->addMonths(1),
            'used_count' => 10, // Đã dùng hết 10 lượt
        ]);

        // 10. Coupon với min_order_value cao
        $coupon10 = Coupon::create([
            'code' => 'CAOCAP',
            'type' => 'public',
            'promotion_type' => 'order',
            'discount_type' => 'percent',
            'discount_value' => 30,
            'min_order_value' => 5000000, // Đơn tối thiểu 5 triệu
            'max_discount' => 1500000, // Giảm tối đa 1.5 triệu
            'usage_limit' => 30,
            'usage_per_user' => 1,
            'starts_at' => now(),
            'expires_at' => now()->addMonths(3),
            'used_count' => 0,
        ]);

        $this->command->info('Đã tạo ' . Coupon::count() . ' coupon mẫu thành công!');
        $this->command->info('Danh sách coupon:');
        Coupon::all()->each(function ($coupon) {
            $this->command->line("- {$coupon->code}: {$coupon->promotion_type}, {$coupon->discount_type}, giá trị: {$coupon->discount_value}");
        });
    }
}