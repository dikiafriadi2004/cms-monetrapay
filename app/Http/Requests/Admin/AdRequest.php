<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdRequest extends FormRequest
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
        $adId = $this->route('ad')?->id ?? null;
        $isUpdate = $adId !== null;

        $rules = [
            'name' => ['required', 'string', 'max:100'],
            'type' => ['required', 'string', Rule::in(['adsense', 'adstera', 'manual'])],
            'code' => ['nullable', 'string', 'max:5000'],
            'image' => ['nullable', 'image', 'max:2048', 'mimes:jpg,jpeg,png,gif'],
            'position' => ['required', 'string', Rule::in(['header', 'sidebar', 'in_content', 'auto_insert', 'footer'])],
            'paragraph_number' => ['nullable', 'integer', 'between:1,20'],
            'interval' => ['nullable', 'integer', 'between:2,10'],
            'is_active' => ['nullable', 'boolean'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after:start_date'],
            'remove_image' => ['nullable', 'boolean'],
        ];

        // Code is required for adsense/adstera types
        if (in_array($this->input('type'), ['adsense', 'adstera'])) {
            $rules['code'] = ['required', 'string', 'max:5000'];
        }

        // Image is required for manual type if no code and no existing image (on create)
        if ($this->input('type') === 'manual' && ! $isUpdate) {
            // For manual type on create, either code or image must be provided
            if (! $this->input('code') && ! $this->hasFile('image')) {
                $rules['code'] = ['required_without:image', 'nullable', 'string', 'max:5000'];
                $rules['image'] = ['required_without:code', 'nullable', 'image', 'max:2048', 'mimes:jpg,jpeg,png,gif'];
            }
        }

        // paragraph_number is required for in_content position
        if ($this->input('position') === 'in_content') {
            $rules['paragraph_number'] = ['required', 'integer', 'between:1,20'];
        }

        // interval is required for auto_insert position
        if ($this->input('position') === 'auto_insert') {
            $rules['interval'] = ['required', 'integer', 'between:2,10'];
        }

        // start_date must not be in the past (only on create)
        if (! $isUpdate && $this->input('start_date')) {
            $rules['start_date'] = ['nullable', 'date', 'after_or_equal:today'];
        }

        // end_date must be after start_date
        if ($this->input('start_date')) {
            $rules['end_date'] = ['nullable', 'date', 'after:start_date'];
        }

        return $rules;
    }

    /**
     * Get custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama slot iklan wajib diisi.',
            'name.max' => 'Nama slot iklan maksimal 100 karakter.',
            'type.required' => 'Tipe iklan wajib dipilih.',
            'type.in' => 'Tipe iklan harus adsense, adstera, atau manual.',
            'code.required' => 'Kode iklan wajib diisi untuk tipe ini.',
            'code.max' => 'Kode iklan maksimal 5.000 karakter.',
            'image.image' => 'File harus berupa gambar.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
            'image.mimes' => 'Gambar harus berformat JPG, PNG, atau GIF.',
            'image.required_without' => 'Gambar atau kode HTML wajib diisi untuk tipe manual.',
            'code.required_without' => 'Kode HTML atau gambar wajib diisi untuk tipe manual.',
            'position.required' => 'Posisi penempatan wajib dipilih.',
            'position.in' => 'Posisi penempatan tidak valid.',
            'paragraph_number.required' => 'Nomor paragraf wajib diisi untuk posisi dalam konten.',
            'paragraph_number.between' => 'Nomor paragraf harus antara 1 sampai 20.',
            'interval.required' => 'Interval paragraf wajib diisi untuk posisi auto-insert.',
            'interval.between' => 'Interval paragraf harus antara 2 sampai 10.',
            'is_active.boolean' => 'Status aktif harus berupa boolean.',
            'start_date.date' => 'Tanggal mulai harus berupa tanggal yang valid.',
            'start_date.after_or_equal' => 'Tanggal mulai tidak boleh di masa lalu.',
            'end_date.date' => 'Tanggal berakhir harus berupa tanggal yang valid.',
            'end_date.after' => 'Tanggal berakhir harus setelah tanggal mulai.',
        ];
    }
}
