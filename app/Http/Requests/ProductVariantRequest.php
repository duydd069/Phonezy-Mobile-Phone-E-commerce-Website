<?php

namespace App\Http\Requests;

use App\Models\ProductVariant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductVariantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $variantId = $this->route('variantId') ?? null;
        $statusKeys = implode(',', array_keys(ProductVariant::statusOptions()));

        // Merge empty strings to null for optional fields
        $this->merge([
            'price_sale' => $this->price_sale === '' ? null : $this->price_sale,
            'sold' => $this->sold === '' ? null : $this->sold,
            'sku' => $this->sku === '' ? null : $this->sku,
            'barcode' => $this->barcode === '' ? null : $this->barcode,
        ]);

        return [
            'price' => ['required', 'numeric', 'min:0'],
            'storage_id' => ['nullable', 'integer', 'exists:storages,id'],
            'version_id' => ['nullable', 'integer', 'exists:versions,id'],
            'color_id' => ['nullable', 'integer', 'exists:colors,id'],
            'price_sale' => ['nullable', 'numeric', 'min:0', 'lte:price'],
            'stock' => ['required', 'integer', 'min:0'],
            'sold' => ['nullable', 'integer', 'min:0'],
            'sku' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('product_variants', 'sku')->ignore($variantId),
            ],
            'barcode' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'status' => ['required', 'in:' . $statusKeys],
        ];
    }

    public function messages(): array
    {
        return [
            'price.required' => 'Vui lòng nhập giá bán',
            'price.numeric' => 'Giá bán phải là số',
            'price.min' => 'Giá bán phải lớn hơn hoặc bằng 0',
            'storage_id.exists' => 'Dung lượng không tồn tại',
            'version_id.exists' => 'Phiên bản không tồn tại',
            'color_id.exists' => 'Màu sắc không tồn tại',
            'price_sale.numeric' => 'Giá khuyến mãi phải là số',
            'price_sale.min' => 'Giá khuyến mãi phải lớn hơn hoặc bằng 0',
            'price_sale.lte' => 'Giá khuyến mãi phải nhỏ hơn hoặc bằng giá bán',
            'stock.required' => 'Vui lòng nhập số lượng tồn kho',
            'stock.integer' => 'Số lượng tồn kho phải là số nguyên',
            'stock.min' => 'Số lượng tồn kho phải lớn hơn hoặc bằng 0',
            'sold.integer' => 'Số lượng đã bán phải là số nguyên',
            'sold.min' => 'Số lượng đã bán phải lớn hơn hoặc bằng 0',
            'sku.max' => 'Mã SKU không được vượt quá 100 ký tự',
            'sku.unique' => 'Mã SKU đã tồn tại',
            'barcode.max' => 'Mã vạch không được vượt quá 100 ký tự',
            'image.image' => 'File phải là hình ảnh',
            'image.mimes' => 'Hình ảnh phải có định dạng: jpg, jpeg, png, webp',
            'image.max' => 'Kích thước hình ảnh không được vượt quá 4MB',
            'status.required' => 'Vui lòng chọn trạng thái',
            'status.in' => 'Trạng thái không hợp lệ',
        ];
    }
}

