<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ColorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $colorId = $this->route('color')?->id ?? null;

        return [
            'name' => [
                'required',
                'string',
                'max:50',
                'unique:colors,name,' . ($colorId ?? 'null') . ',id'
            ],
            'hex_code' => [
                'nullable',
                'string',
                'max:10',
                'regex:/^#([0-9A-Fa-f]{3}|[0-9A-Fa-f]{6})$/',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên màu sắc',
            'name.max' => 'Tên màu sắc không được vượt quá 50 ký tự',
            'name.unique' => 'Tên màu sắc đã tồn tại',
            'hex_code.max' => 'Mã màu hex không được vượt quá 10 ký tự',
            'hex_code.regex' => 'Mã màu hex không hợp lệ. Định dạng: #RGB hoặc #RRGGBB',
        ];
    }
}

