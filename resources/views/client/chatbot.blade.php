@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3">
        <div>
            <h1 class="h3 mb-1">Trợ lý mua sắm AI</h1>
            <p class="text-muted mb-0">Đặt câu hỏi về sản phẩm, khuyến mãi hoặc nhu cầu của bạn.</p>
        </div>
        <a href="{{ route('client.index') }}" class="btn btn-outline-secondary">← Quay lại trang chủ</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div id="chatbotLog" class="border rounded p-3 mb-3" style="min-height: 280px; max-height: 420px; overflow-y: auto;">
                <div class="text-muted">Xin chào! Tôi là trợ lý AI của cửa hàng. Bạn cần tư vấn gì hôm nay?</div>
            </div>
            <form id="chatbotForm" class="input-group">
                <input type="text" name="message" class="form-control" placeholder="Nhập câu hỏi..." required autocomplete="off" />
                <button class="btn btn-primary" type="submit">Gửi</button>
            </form>
            <div class="form-text mt-2">
                Chatbot sẽ dùng dữ liệu sản phẩm & khuyến mãi thực tế. Thiếu khóa API sẽ tự động trả lời bằng dữ liệu nội bộ.
            </div>
        </div>
    </div>
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('chatbotForm');
    const log = document.getElementById('chatbotLog');
    const input = form.querySelector('input[name="message"]');

    const appendMessage = (text, author = 'bot') => {
        const bubble = document.createElement('div');
        bubble.classList.add('mb-2', 'p-2', 'rounded');
        if (author === 'bot') {
            bubble.classList.add('bg-light');
        } else {
            bubble.classList.add('bg-primary', 'text-white', 'text-end');
        }
        bubble.textContent = text;
        log.appendChild(bubble);
        log.scrollTop = log.scrollHeight;
    };

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        const message = input.value.trim();
        if (!message) return;

        appendMessage(message, 'user');
        input.value = '';
        input.focus();
        appendMessage('Đang xử lý...', 'bot');

        try {
            const response = await fetch('{{ route('api.chatbot') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ message }),
            });

            const data = await response.json();
            log.lastChild.remove(); // remove loading message

            if (data.success) {
                appendMessage(data.data.answer, 'bot');
                if (data.data.suggestions?.length) {
                    const suggestionText = data.data.suggestions
                        .map(product => `• ${product.name} - ${new Intl.NumberFormat('vi-VN').format(product.price)}đ (${product.slug})`)
                        .join('\n');
                    appendMessage(`Các lựa chọn gợi ý:\n${suggestionText}`, 'bot');
                }
            } else {
                appendMessage('Xin lỗi, tôi chưa thể xử lý yêu cầu này.', 'bot');
            }
        } catch (error) {
            log.lastChild.remove();
            appendMessage('Có lỗi trong quá trình kết nối. Vui lòng thử lại sau.', 'bot');
        }
    });
});
</script>

