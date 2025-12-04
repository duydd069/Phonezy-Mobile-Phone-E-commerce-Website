@extends('electro.layout')

@section('title', 'Xác thực Email')

@section('content')
<div class="section">
  <div class="container">
    <div class="row">
      <div class="col-md-8 offset-md-2">
        <div class="billing-details card" style="padding:40px; margin-top:50px; text-align:center;">
          <div style="font-size: 72px; color: #3490dc; margin-bottom: 20px;">
            📧
          </div>
          
          <h2 class="section-title">Kiểm tra Email của bạn</h2>
          
          <div style="margin: 30px 0;">
            <p style="font-size: 16px; color: #666; line-height: 1.8;">
              Chúng tôi đã gửi một email xác thực đến địa chỉ:
            </p>
            <p style="font-size: 18px; font-weight: bold; color: #333; margin: 15px 0;">
              {{ session('email') }}
            </p>
            <p style="font-size: 16px; color: #666; line-height: 1.8;">
              Vui lòng kiểm tra hộp thư đến (và cả thư mục spam) để xác thực tài khoản của bạn.
            </p>
          </div>

          <div class="alert alert-info" style="text-align: left; margin: 25px 0;">
            <strong>📌 Lưu ý:</strong>
            <ul style="margin-top: 10px; margin-bottom: 0;">
              <li>Link xác thực sẽ hết hạn sau <strong>24 giờ</strong></li>
              <li>Nếu không thấy email, hãy kiểm tra thư mục spam/junk</li>
              <li>Bạn chỉ có thể đăng nhập sau khi xác thực email</li>
            </ul>
          </div>

          <div style="margin-top: 30px;">
            <a href="{{ route('client.index') }}" class="primary-btn" style="margin-right: 10px;">
              Về Trang Chủ
            </a>
            <a href="{{ route('login') }}" class="primary-btn" style="background-color: #6c757d;">
              Đến Trang Đăng Nhập
            </a>
          </div>

          <p style="margin-top: 30px; font-size: 14px; color: #999;">
            Không nhận được email? Liên hệ với chúng tôi để được hỗ trợ.
          </p>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
.alert-info {
  background-color: #d1ecf1;
  border-color: #bee5eb;
  color: #0c5460;
  padding: 15px;
  border-radius: 5px;
  border-left: 4px solid #17a2b8;
}
.alert-info ul {
  padding-left: 20px;
}
.alert-info li {
  margin-bottom: 5px;
}
</style>
@endsection
