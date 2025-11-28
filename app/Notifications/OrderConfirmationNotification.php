<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderConfirmationNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Order $order
    ) {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $order = $this->order;
        $order->load('items');

        $message = (new MailMessage)
            ->subject('Xác nhận đơn hàng #' . $order->id)
            ->greeting('Xin chào ' . $order->shipping_full_name . '!')
            ->line('Cảm ơn bạn đã đặt hàng tại ' . config('app.name') . '.')
            ->line('Đơn hàng của bạn đã được nhận và đang được xử lý.')
            ->line('Mã đơn hàng: #' . $order->id)
            ->line('Tổng tiền: ' . number_format($order->total, 0, ',', '.') . ' ₫')
            ->line('Phương thức thanh toán: ' . ($order->payment_method === 'cod' ? 'Thanh toán khi nhận hàng' : $order->payment_method))
            ->line('Trạng thái: ' . ucfirst($order->status))
            ->line('---');

        // Thêm danh sách sản phẩm
        $message->line('Sản phẩm đã đặt:');
        foreach ($order->items as $item) {
            $message->line('- ' . $item->product_name . ' x' . $item->quantity . ' - ' . number_format($item->total_price, 0, ',', '.') . ' ₫');
        }

        // Thông tin địa chỉ
        $message->line('---')
            ->line('Địa chỉ giao hàng:')
            ->line($order->shipping_address);

        $addressParts = array_filter([
            $order->shipping_ward,
            $order->shipping_district,
            $order->shipping_city
        ]);

        if (!empty($addressParts)) {
            $message->line(implode(', ', $addressParts));
        }

        $message->line('Số điện thoại: ' . $order->shipping_phone)
            ->line('Chúng tôi sẽ liên hệ với bạn sớm nhất có thể để xác nhận đơn hàng.')
            ->line('Cảm ơn bạn đã tin tưởng và sử dụng dịch vụ của chúng tôi!');

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'order_total' => $this->order->total,
        ];
    }
}
