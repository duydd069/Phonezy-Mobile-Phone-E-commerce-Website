<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $storageId = $this->route('storage')?->id ?? null;

        return [
            'storage' => [
                'required',
                'string',
                'max:50',
                'unique:storages,storage,' . ($storageId ?? 'null') . ',id'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'storage.required' => 'Vui lòng nhập dung lượng',
            'storage.max' => 'Dung lượng không được vượt quá 50 ký tự',
            'storage.unique' => 'Dung lượng đã tồn tại',
        ];
    }
}

