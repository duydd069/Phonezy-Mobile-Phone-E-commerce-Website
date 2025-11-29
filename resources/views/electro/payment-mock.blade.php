@extends('electro.layout')

@section('title', 'Thanh toán MoMo (Test Mode)')

@section('content')
<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="billing-details" style="background: #fff; padding: 30px; border-radius: 5px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                    <div class="section-title" style="text-align: center; margin-bottom: 30px;">
                        <h3 class="title" style="color: #D10024;">
                            <i class="fa fa-mobile" style="font-size: 48px; margin-bottom: 20px; display: block;"></i>
                            Thanh toán MoMo (Test Mode)
                        </h3>
                        <p style="color: #666; font-size: 14px; margin-top: 10px;">
                            Đây là trang test thanh toán. Trong môi trường thật, bạn sẽ được chuyển đến MoMo để thanh toán.
                        </p>
                    </div>

                    <div style="background: #f8f9fa; padding: 20px; border-radius: 5px; margin-bottom: 20px;">
                        <div style="margin-bottom: 15px;">
                            <strong>Mã đơn hàng:</strong>
                            <span style="float: right; color: #D10024;">{{ $orderId }}</span>
                        </div>
                        <div style="margin-bottom: 15px;">
                            <strong>Số tiền:</strong>
                            <span style="float: right; color: #D10024; font-size: 18px; font-weight: bold;">
                                {{ number_format($amount, 0, ',', '.') }} ₫
                            </span>
                        </div>
                        @if($orderInfo)
                        <div>
                            <strong>Thông tin:</strong>
                            <span style="float: right;">{{ $orderInfo }}</span>
                        </div>
                        @endif
                    </div>

                    <div style="background: #fff3cd; border: 1px solid #ffc107; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                        <p style="margin: 0; color: #856404;">
                            <i class="fa fa-info-circle"></i> 
                            <strong>Lưu ý:</strong> Đây là chế độ test. Không có giao dịch thật nào được thực hiện.
                        </p>
                    </div>

                    <form action="{{ route('client.payment.momo.mock.process') }}" method="POST">
                        @csrf
                        <input type="hidden" name="orderId" value="{{ $orderId }}">
                        
                        <div style="display: flex; gap: 10px;">
                            <button type="submit" name="action" value="success" 
                                    style="flex: 1; background: #D10024; color: white; border: none; padding: 15px; border-radius: 5px; font-size: 16px; font-weight: bold; cursor: pointer; transition: background 0.3s;"
                                    onmouseover="this.style.background='#b8001f'" 
                                    onmouseout="this.style.background='#D10024'">
                                <i class="fa fa-check-circle"></i> Thanh toán thành công
                            </button>
                            
                            <button type="submit" name="action" value="cancel" 
                                    style="flex: 1; background: #6c757d; color: white; border: none; padding: 15px; border-radius: 5px; font-size: 16px; font-weight: bold; cursor: pointer; transition: background 0.3s;"
                                    onmouseover="this.style.background='#5a6268'" 
                                    onmouseout="this.style.background='#6c757d'">
                                <i class="fa fa-times-circle"></i> Hủy thanh toán
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

