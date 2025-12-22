@extends('layouts.app')

@section('content')
<div class="container text-center" style="padding: 100px 0;">
    <div class="error-page">
        <h1 style="font-size: 100px; color: #e67e22; margin-bottom: 20px;">500</h1>
        <h2 style="font-size: 30px; margin-bottom: 20px;">Lỗi máy chủ</h2>
        <p style="font-size: 18px; color: #7f8c8d; margin-bottom: 30px;">
            Đã xảy ra lỗi không mong muốn. Vui lòng thử lại sau.
        </p>
        <a href="{{ route('client.index') }}" class="btn btn-primary btn-lg">
            <i class="fa fa-home"></i> Về trang chủ
        </a>
    </div>
</div>
@endsection
