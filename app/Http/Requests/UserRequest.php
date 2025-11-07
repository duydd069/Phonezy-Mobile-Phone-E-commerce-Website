<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user')?->id ?? null;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . ($userId ?? 'null') . ',id'],
            'password' => [$userId ? 'nullable' : 'required', 'string', 'min:8'],
            'is_admin' => ['nullable', 'boolean'],
            'email_verified_at' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên tài khoản.',
            'name.string' => 'Tên tài khoản phải là chuỗi ký tự.',
            'name.max' => 'Tên tài khoản không được vượt quá 255 ký tự.',

            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.string' => 'Email phải là chuỗi ký tự.',
            'email.email' => 'Email không đúng định dạng.',
            'email.max' => 'Email không được vượt quá 255 ký tự.',
            'email.unique' => 'Email đã tồn tại trong hệ thống.',

            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.string' => 'Mật khẩu phải là chuỗi ký tự.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',

            'is_admin.boolean' => 'Giá trị của quyền quản trị không hợp lệ.',

            'email_verified_at.date' => 'Ngày xác thực email không đúng định dạng.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'tên tài khoản',
            'email' => 'email',
            'password' => 'mật khẩu',
            'is_admin' => 'quyền quản trị',
            'email_verified_at' => 'thời gian xác thực email',
        ];
    }
}

