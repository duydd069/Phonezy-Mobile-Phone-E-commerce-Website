<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $paymentMethods = array_keys(config('checkout.payment_methods', []));
        $isGuest = !auth()->check();

        return [
            'full_name'      => ['required', 'string', 'max:120'],
            'email'          => [$isGuest ? 'required' : 'nullable', 'email', 'max:150'],
            'phone'          => ['required', 'string', 'max:30'],
            'address'        => ['required', 'string', 'max:255'],
            'city'           => ['nullable', 'string', 'max:120'],
            'district'       => ['nullable', 'string', 'max:120'],
            'ward'           => ['nullable', 'string', 'max:120'],
            'notes'          => ['nullable', 'string', 'max:500'],
            'payment_method' => ['required', Rule::in($paymentMethods)],
        ];
    }

    public function attributes(): array
    {
        return [
            'full_name'      => 'họ và tên',
            'email'          => 'email',
            'phone'          => 'số điện thoại',
            'address'        => 'địa chỉ',
            'city'           => 'tỉnh/thành',
            'district'       => 'quận/huyện',
            'ward'           => 'phường/xã',
            'notes'          => 'ghi chú',
            'payment_method' => 'phương thức thanh toán',
        ];
    }
}


