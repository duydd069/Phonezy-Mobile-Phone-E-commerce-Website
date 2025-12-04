<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác thực Email</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            background-color: #f9f9f9;
            border-radius: 10px;
            padding: 30px;
            border: 1px solid #e0e0e0;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 32px;
            font-weight: bold;
            color: #3490dc;
        }
        .content {
            background-color: white;
            padding: 25px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .button {
            display: inline-block;
            padding: 15px 30px;
            background-color: #3490dc;
            color: white !important;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
        }
        .button:hover {
            background-color: #2779bd;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #999;
            margin-top: 20px;
        }
        .warning {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 12px;
            margin-top: 20px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">📱 Phonezy</div>
        </div>

        <div class="content">
            <h2>Xin chào {{ $userName }}!</h2>
            <p>Cảm ơn bạn đã đăng ký tài khoản tại <strong>Phonezy</strong>.</p>
            <p>Để hoàn tất quá trình đăng ký, vui lòng xác thực địa chỉ email của bạn bằng cách nhấn vào nút bên dưới:</p>
            
            <div style="text-align: center;">
                <a href="{{ $verificationUrl }}" class="button">Xác thực Email</a>
            </div>

            <p style="margin-top: 25px;">Hoặc bạn có thể copy link sau và dán vào trình duyệt:</p>
            <p style="word-break: break-all; background-color: #f5f5f5; padding: 10px; border-radius: 4px; font-size: 13px;">
                {{ $verificationUrl }}
            </p>

            <div class="warning">
                <strong>⚠️ Lưu ý:</strong> Link xác thực này sẽ hết hạn sau <strong>24 giờ</strong>. Nếu bạn không thực hiện yêu cầu này, vui lòng bỏ qua email này.
            </div>
        </div>

        <div class="footer">
            <p>© {{ date('Y') }} Phonezy. All rights reserved.</p>
            <p>Email này được gửi tự động, vui lòng không trả lời.</p>
        </div>
    </div>
</body>
</html>
