<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $categoryId = $this->route('category')?->id ?? null;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:categories,name,' . ($categoryId ?? 'null') . ',id'
            ],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                'unique:categories,slug,' . ($categoryId ?? 'null') . ',id'
            ],
            'description' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên danh mục',
            'name.max' => 'Tên danh mục không được vượt quá 255 ký tự',
            'name.unique' => 'Tên danh mục đã tồn tại',
            'slug.max' => 'Slug không được vượt quá 255 ký tự',
            'slug.unique' => 'Slug đã tồn tại',
        ];
    }
}

