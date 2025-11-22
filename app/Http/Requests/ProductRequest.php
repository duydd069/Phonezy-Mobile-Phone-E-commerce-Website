<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = $this->route('id') ?? null;

        return [
            'name' => ['required', 'string', 'max:200'],
            'price' => ['required', 'numeric', 'min:0'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'brand_id' => ['required', 'integer', 'exists:brands,id'],
            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096'
            ],
            'description' => ['nullable', 'string'],
            'extra_images' => ['nullable', 'array', 'max:10'],
            'extra_images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'remove_images' => ['nullable', 'array'],
            'remove_images.*' => ['integer', 'exists:product_images,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên sản phẩm',
            'name.max' => 'Tên sản phẩm không được vượt quá 200 ký tự',
            'price.required' => 'Vui lòng nhập giá sản phẩm',
            'price.numeric' => 'Giá sản phẩm phải là số',
            'price.min' => 'Giá sản phẩm phải lớn hơn hoặc bằng 0',
            'category_id.required' => 'Vui lòng chọn danh mục',
            'category_id.exists' => 'Danh mục không tồn tại',
            'brand_id.required' => 'Vui lòng chọn thương hiệu',
            'brand_id.exists' => 'Thương hiệu không tồn tại',
            'image.image' => 'File phải là hình ảnh',
            'image.mimes' => 'Hình ảnh phải có định dạng: jpg, jpeg, png, webp',
            'image.max' => 'Kích thước hình ảnh không được vượt quá 4MB',
            'extra_images.max' => 'Số lượng hình ảnh bổ sung không được vượt quá 10',
            'extra_images.*.image' => 'Tất cả các file phải là hình ảnh',
            'extra_images.*.mimes' => 'Hình ảnh phải có định dạng: jpg, jpeg, png, webp',
            'extra_images.*.max' => 'Kích thước mỗi hình ảnh không được vượt quá 4MB',
            'remove_images.*.exists' => 'Hình ảnh không tồn tại',
        ];
    }
}

