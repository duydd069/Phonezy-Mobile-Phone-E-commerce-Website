@extends('layouts.app')

@section('content')
<div class="container text-center" style="padding: 100px 0;">
    <div class="error-page">
        <h1 style="font-size: 100px; color: #3498db; margin-bottom: 20px;">404</h1>
        <h2 style="font-size: 30px; margin-bottom: 20px;">Không tìm thấy trang</h2>
        <p style="font-size: 18px; color: #7f8c8d; margin-bottom: 30px;">
            Trang bạn đang tìm kiếm không tồn tại hoặc đã bị di chuyển.
        </p>
        <a href="{{ route('client.index') }}" class="btn btn-primary btn-lg">
            <i class="fa fa-home"></i> Về trang chủ
        </a>
    </div>
</div>
@endsection
