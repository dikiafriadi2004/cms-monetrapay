<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class MenuItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'label' => ['required', 'string', 'max:100'],
            'type' => ['required', 'string', 'in:custom,page,category,external'],
            'url' => ['required_if:type,custom,external', 'nullable', 'url', 'max:500'],
            'target' => ['nullable', 'string', 'in:_self,_blank'],
            'page_id' => ['required_if:type,page', 'nullable', 'integer', 'exists:pages,id'],
            'category_id' => ['required_if:type,category', 'nullable', 'integer', 'exists:categories,id'],
        ];
    }

    /**
     * Get custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'label.required' => 'Label item menu wajib diisi.',
            'label.max' => 'Label maksimal 100 karakter.',
            'type.required' => 'Tipe item menu wajib dipilih.',
            'type.in' => 'Tipe item menu harus salah satu dari: custom, page, category, external.',
            'url.required_if' => 'URL wajib diisi untuk tipe custom link atau external URL.',
            'url.url' => 'URL harus berformat valid (dimulai http:// atau https://).',
            'url.max' => 'URL maksimal 500 karakter.',
            'page_id.required_if' => 'Halaman wajib dipilih untuk tipe page.',
            'page_id.exists' => 'Halaman yang dipilih tidak valid.',
            'category_id.required_if' => 'Kategori wajib dipilih untuk tipe category.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
        ];
    }
}
