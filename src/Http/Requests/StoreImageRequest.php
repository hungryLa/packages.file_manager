<?php

namespace Niyama\FileManager\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'files' => 'array',
            'files.*' => ['file','mimes:jpg,jpeg,png,webp'],
            'images' => 'array',
            'images.*' => ['file','mimes:jpg,jpeg,png,webp'],
        ];
    }

    public function messages()
    {
        return [
            'files.*' => [
                'mimes' => 'Файл(ы) должны быть файлом одного из типов: :values.'
            ]
        ];
    }

}