<style>
    #ai-assistant-toggle {
        position: fixed;
        right: 20px;
        bottom: 20px;
        width: 56px;
        height: 56px;
        border-radius: 50%;
        border: none;
        background: linear-gradient(135deg, #ff7b54, #ffb347);
        color: #fff;
        font-size: 22px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        cursor: pointer;
        z-index: 1060;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #ai-assistant-panel {
        position: fixed;
        right: 20px;
        bottom: 90px;
        width: 320px;
        max-width: calc(100% - 40px);
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 20px 45px rgba(0,0,0,0.15);
        overflow: hidden;
        display: none;
        flex-direction: column;
        z-index: 1055;
    }

    #ai-assistant-panel.show {
        display: flex;
    }

    #ai-assistant-panel .assistant-header {
        background: linear-gradient(135deg, #ff8f70 0%, #ff65a3 100%);
        color: #fff;
        padding: 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    #ai-assistant-panel .assistant-header .assistant-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    #ai-assistant-panel .assistant-header img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(255,255,255,0.2);
        object-fit: cover;
    }

    #ai-assistant-panel .assistant-body {
        padding: 16px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        height: 360px;
    }

    #assistantMessages {
        flex: 1;
        overflow-y: auto;
        padding-right: 4px;
    }

    .assistant-bubble {
        padding: 10px 14px;
        border-radius: 12px;
        margin-bottom: 8px;
        font-size: 14px;
        line-height: 1.4;
        white-space: pre-line;
    }

    .assistant-bubble.bot {
        background: #f5f7fb;
        color: #333;
        border-bottom-left-radius: 4px;
    }

    .assistant-bubble.user {
        background: #ff8f70;
        color: #fff;
        border-bottom-right-radius: 4px;
        margin-left: auto;
    }

    #ai-assistant-panel form {
        display: flex;
        gap: 8px;
    }

    #ai-assistant-panel input {
        flex: 1;
        border-radius: 999px;
        border: 1px solid #e5e7eb;
        padding: 10px 14px;
    }

    #ai-assistant-panel button[type="submit"] {
        border-radius: 50%;
        width: 44px;
        height: 44px;
        border: none;
        background: #ff8f70;
        color: #fff;
    }
</style>

<button id="ai-assistant-toggle" aria-label="Trợ lý AI">
    <i class="fa fa-comments"></i>
</button>

<div id="ai-assistant-panel" role="dialog" aria-modal="true">
    <div class="assistant-header">
        <div class="assistant-info">
            <img src="{{ asset('electro/img/assistant-avatar.png') }}" alt="AI Assistant" onerror="this.style.display='none'">
            <div>
                <strong>Trợ lý Electro</strong>
                <div style="font-size:12px; opacity:0.85;">Sẵn sàng hỗ trợ</div>
            </div>
        </div>
        <button class="btn btn-sm btn-link text-white" id="ai-assistant-close" style="text-decoration:none;">✕</button>
    </div>
    <div class="assistant-body">
        <div id="assistantMessages">
            <div class="assistant-bubble bot">
                Xin chào! Mình có thể giúp bạn tìm sản phẩm hoặc tư vấn khuyến mãi.
            </div>
        </div>
        <form id="assistantForm">
            <input type="text" name="message" placeholder="Nhập câu hỏi..." autocomplete="off" required>
            <button type="submit">
                <i class="fa fa-paper-plane"></i>
            </button>
        </form>
        <small class="text-muted text-center">Powered by AI & dữ liệu cửa hàng</small>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const toggleBtn = document.getElementById('ai-assistant-toggle');
        const panel = document.getElementById('ai-assistant-panel');
        const closeBtn = document.getElementById('ai-assistant-close');
        const form = document.getElementById('assistantForm');
        const messages = document.getElementById('assistantMessages');
        const input = form.querySelector('input[name="message"]');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const appendMessage = (text, type = 'bot') => {
            const bubble = document.createElement('div');
            bubble.classList.add('assistant-bubble', type);
            bubble.textContent = text;
            messages.appendChild(bubble);
            messages.scrollTop = messages.scrollHeight;
        };

        toggleBtn.addEventListener('click', () => {
            panel.classList.toggle('show');
            if (panel.classList.contains('show')) {
                input.focus();
            }
        });

        closeBtn.addEventListener('click', () => {
            panel.classList.remove('show');
        });

        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            const message = input.value.trim();
            if (!message) return;
            appendMessage(message, 'user');
            input.value = '';
            input.focus();
            appendMessage('Đang soạn trả lời...', 'bot');

            try {
                const response = await fetch('{{ route('api.chatbot') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ message }),
                });

                messages.lastChild.remove(); // remove loading bubble

                // Kiểm tra response status
                if (!response.ok) {
                    appendMessage('Xin lỗi, mình đang có chút trục trặc. Bạn thử lại sau nhé!', 'bot');
                    return;
                }

                // Kiểm tra content-type để đảm bảo là JSON
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    appendMessage('Xin lỗi, mình đang có chút trục trặc. Bạn thử lại sau nhé!', 'bot');
                    return;
                }

                const data = await response.json();

                if (data.success) {
                    appendMessage(data.data.answer || 'Mình đã ghi nhận thông tin của bạn!', 'bot');
                    if (data.data.suggestions?.length) {
                        const suggestionText = data.data.suggestions
                            .map(product => `• ${product.name} - ${new Intl.NumberFormat('vi-VN').format(product.price)}đ`)
                            .join('\n');
                        appendMessage(`Gợi ý nổi bật:\n${suggestionText}`, 'bot');
                    }
                } else {
                    appendMessage(data.error || 'Xin lỗi, mình đang có chút trục trặc. Bạn thử lại sau nhé!', 'bot');
                }
            } catch (error) {
                messages.lastChild.remove();
                console.error('Chatbot error:', error);
                appendMessage('Kết nối bị gián đoạn, vui lòng thử lại.', 'bot');
            }
        });
    });
</script>
@endpush

