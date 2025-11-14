<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $brandId = $this->route('brand')?->id ?? null;
        $logoRules = $this->isMethod('post')
            ? ['required', 'image', 'mimes:jpeg,png,webp', 'max:2048']
            : ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048'];

        return [
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:150', 'unique:brands,slug,' . ($brandId ?? 'null') . ',id'],
            'logo' => $logoRules,
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập đầy đủ thông tin',
            'logo.required' => 'Vui lòng chọn logo thương hiệu',
        ];
    }
}


