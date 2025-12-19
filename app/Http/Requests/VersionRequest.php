<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VersionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $versionId = $this->route('version')?->id ?? null;

        return [
            'name' => [
                'required',
                'string',
                'max:50',
                'unique:versions,name,' . ($versionId ?? 'null') . ',id'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên phiên bản',
            'name.max' => 'Tên phiên bản không được vượt quá 50 ký tự',
            'name.unique' => 'Tên phiên bản đã tồn tại',
        ];
    }
}

