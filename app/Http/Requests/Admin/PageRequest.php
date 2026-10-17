<?php

namespace App\Http\Requests\Admin;

use App\Models\Page;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PageRequest extends FormRequest
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
        $pageId = $this->route('page')?->id ?? null;

        $rules = [
            'title' => ['required', 'string', 'max:200'],
            'content' => ['nullable', 'string', 'max:100000'],
            'parent_id' => ['nullable', 'integer', 'exists:pages,id'],
            'template' => ['nullable', 'string', 'in:default,full-width,sidebar-left,sidebar-right'],
            'status' => ['required', 'in:draft,published'],
            'featured_image' => ['nullable', 'image', 'max:2048', 'mimes:jpg,jpeg,png,webp'],
            'meta_title' => ['nullable', 'string', 'max:60'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
        ];

        // Slug validation
        if ($pageId) {
            $rules['slug'] = [
                'nullable',
                'string',
                'max:200',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('pages', 'slug')->ignore($pageId)->withoutTrashed(),
            ];
            $rules['remove_featured_image'] = ['nullable', 'boolean'];
        } else {
            $rules['slug'] = [
                'nullable',
                'string',
                'max:200',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
            ];
        }

        return $rules;
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->filled('parent_id')) {
                $parent = Page::find($this->parent_id);

                if ($parent) {
                    $parentDepth = $this->getPageDepth($parent);

                    // If parent is at depth 2, adding a child would make depth 3 (max allowed)
                    // If parent is at depth 3 or more, reject
                    if ($parentDepth >= 3) {
                        $validator->errors()->add(
                            'parent_id',
                            'Halaman tidak dapat ditambahkan di level ini. Maksimal 3 level kedalaman.'
                        );
                    }

                    // Prevent setting a page as its own parent or descendant
                    $pageId = $this->route('page')?->id ?? null;
                    if ($pageId) {
                        if ($parent->id === $pageId) {
                            $validator->errors()->add(
                                'parent_id',
                                'Halaman tidak dapat menjadi parent dari dirinya sendiri.'
                            );
                        }

                        // Check if the selected parent is a descendant of the current page
                        if ($this->isDescendant($parent, $pageId)) {
                            $validator->errors()->add(
                                'parent_id',
                                'Halaman tidak dapat menjadi child dari turunannya sendiri.'
                            );
                        }
                    }
                }
            }
        });
    }

    /**
     * Calculate the depth of a page in the hierarchy.
     * Root pages have depth 1, their children depth 2, etc.
     */
    protected function getPageDepth(Page $page): int
    {
        $depth = 1;
        $current = $page;

        while ($current->parent_id) {
            $depth++;
            $current = $current->parent;

            // Safety check to prevent infinite loops
            if ($depth > 10) {
                break;
            }
        }

        return $depth;
    }

    /**
     * Check if a page is a descendant of another page.
     */
    protected function isDescendant(Page $page, int $ancestorId): bool
    {
        $current = $page;

        while ($current->parent_id) {
            if ($current->parent_id === $ancestorId) {
                return true;
            }
            $current = $current->parent;

            // Safety check
            if (! $current) {
                break;
            }
        }

        return false;
    }

    /**
     * Get custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Judul halaman wajib diisi.',
            'title.max' => 'Judul maksimal 200 karakter.',
            'slug.max' => 'Slug maksimal 200 karakter.',
            'slug.regex' => 'Slug hanya boleh berisi huruf kecil, angka, dan tanda hubung.',
            'slug.unique' => 'Slug sudah digunakan oleh halaman lain.',
            'content.max' => 'Konten maksimal 100.000 karakter.',
            'parent_id.exists' => 'Halaman parent yang dipilih tidak valid.',
            'template.in' => 'Template yang dipilih tidak valid.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status harus draft atau published.',
            'featured_image.image' => 'Featured image harus berupa file gambar.',
            'featured_image.max' => 'Featured image maksimal 2MB.',
            'featured_image.mimes' => 'Featured image harus berformat JPG, PNG, atau WebP.',
            'meta_title.max' => 'Meta title maksimal 60 karakter.',
            'meta_description.max' => 'Meta description maksimal 160 karakter.',
            'meta_keywords.max' => 'Meta keywords maksimal 500 karakter.',
        ];
    }
}
