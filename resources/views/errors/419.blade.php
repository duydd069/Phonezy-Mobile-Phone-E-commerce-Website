@extends('layouts.app')

@section('content')
<div class="container text-center" style="padding: 100px 0;">
    <div class="error-page">
        <h1 style="font-size: 100px; color: #e74c3c; margin-bottom: 20px;">419</h1>
        <h2 style="font-size: 30px; margin-bottom: 20px;">Phiên làm việc đã hết hạn</h2>
        <p style="font-size: 18px; color: #7f8c8d; margin-bottom: 30px;">
            Phiên làm việc của bạn đã hết hạn. Vui lòng tải lại trang và thử lại.
        </p>
        <a href="javascript:window.location.reload();" class="btn btn-primary btn-lg">
            <i class="fa fa-refresh"></i> Tải lại trang
        </a>
        <a href="{{ route('client.index') }}" class="btn btn-secondary btn-lg ms-2">
            <i class="fa fa-home"></i> Về trang chủ
        </a>
    </div>
</div>
@endsection
