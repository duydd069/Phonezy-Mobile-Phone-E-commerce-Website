<?php

namespace App\Notifications;

use App\Models\OrderReturn;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RefundProcessedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public OrderReturn $return
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
        $return = $this->return;
        $return->load('order.items');
        $order = $return->order;

        $message = (new MailMessage)
            ->subject('Đã hoàn tiền cho đơn hàng #' . $order->id)
            ->greeting('Xin chào ' . $order->shipping_full_name . '!')
            ->line('Chúng tôi đã xử lý và hoàn tiền thành công cho yêu cầu trả hàng của bạn.')
            ->line('---')
            ->line('Thông tin đơn hàng:')
            ->line('Mã đơn hàng: #' . $order->id)
            ->line('Mã yêu cầu trả hàng: #' . $return->id)
            ->line('Số tiền hoàn: ' . number_format($order->total, 0, ',', '.') . ' ₫')
            ->line('Ngày hoàn tiền: ' . $return->refunded_at?->format('d/m/Y H:i'))
            ->line('---');

        // Thêm lý do trả hàng
        if ($return->reason) {
            $message->line('Lý do trả hàng: ' . $return->reason);
        }

        // Thêm ghi chú nếu có
        if ($return->admin_notes) {
            $message->line('Ghi chú: ' . $return->admin_notes);
        }

        $message->line('---')
            ->line('Danh sách sản phẩm đã trả:');
        
        foreach ($order->items as $item) {
            $message->line('- ' . $item->product_name . ' x' . $item->quantity . ' - ' . number_format($item->total_price, 0, ',', '.') . ' ₫');
        }

        $message->line('---')
            ->line('Tiền hoàn sẽ được chuyển về tài khoản/phương thức thanh toán ban đầu trong vòng 3-5 ngày làm việc.')
            ->line('Nếu có bất kỳ thắc mắc nào, vui lòng liên hệ với chúng tôi.')
            ->line('Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi!');

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
            'return_id' => $this->return->id,
            'order_id' => $this->return->order_id,
            'refund_amount' => $this->return->order->total,
        ];
    }
}
