<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageComponent;
use App\Traits\RespondsWithError;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageBuilderController extends Controller
{
    use RespondsWithError, AuthorizesRequests;

    /**
     * Available component types
     */
    protected array $componentTypes = [
        'hero' => ['name' => 'Hero Section', 'icon' => 'star', 'category' => 'headers'],
        'text' => ['name' => 'Text Block', 'icon' => 'text', 'category' => 'content'],
        'gallery' => ['name' => 'Image Gallery', 'icon' => 'image', 'category' => 'media'],
        'contact-form' => ['name' => 'Contact Form', 'icon' => 'mail', 'category' => 'forms'],
        'cta' => ['name' => 'Call to Action', 'icon' => 'arrow-right', 'category' => 'conversion'],
        'features' => ['name' => 'Features Grid', 'icon' => 'grid', 'category' => 'content'],
        'testimonials' => ['name' => 'Testimonials', 'icon' => 'message-square', 'category' => 'social'],
        'pricing' => ['name' => 'Pricing Tables', 'icon' => 'dollar-sign', 'category' => 'conversion'],
        'team' => ['name' => 'Team Members', 'icon' => 'users', 'category' => 'content'],
        'faq' => ['name' => 'FAQ Accordion', 'icon' => 'help-circle', 'category' => 'content'],
        'video' => ['name' => 'Video Embed', 'icon' => 'video', 'category' => 'media'],
        'map' => ['name' => 'Map Location', 'icon' => 'map-pin', 'category' => 'content'],
        'social' => ['name' => 'Social Media', 'icon' => 'share-2', 'category' => 'social'],
        'newsletter' => ['name' => 'Newsletter Signup', 'icon' => 'send', 'category' => 'forms'],
        'html' => ['name' => 'Custom HTML', 'icon' => 'code', 'category' => 'advanced'],
    ];

    /**
     * Show page builder index (list of pages using page builder)
     */
    public function index(): View
    {
        return view('admin.page-builder.index');
    }

    /**
     * Show page builder editor
     */
    public function edit(Page $page)
    {
        // Check if page exists
        if (!$page || !$page->exists) {
            return redirect()->route('admin.pages.index')
                ->with('error', 'Page not found. Please select a valid page.');
        }

        // Check if page builder is enabled for this page
        if (!$page->use_page_builder) {
            return redirect()->route('admin.pages.edit', $page->id)
                ->with('warning', 'Page Builder is not enabled for this page. Please enable "Use Page Builder" toggle first.');
        }

        // Load components relationship
        $page->load('components');

        return view('admin.page-builder.edit', [
            'page' => $page,
            'componentTypes' => $this->componentTypes,
        ]);
    }

    /**
     * Get available components (API)
     */
    public function components(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'components' => $this->componentTypes,
        ]);
    }

    /**
     * Save page components.
     * Accepts two formats:
     *  - JSON bulk: { components: [{component_type, content, settings, sort_order, is_visible}] }
     *  - Form single: component_type, content (JSON string), sort_order, is_visible
     */
    public function save(Request $request, Page $page): JsonResponse|RedirectResponse
    {
        try {
            // --- Format 1: JSON bulk save (future drag-drop UI) ---
            if ($request->has('components') && is_array($request->input('components'))) {
                $validated = $request->validate([
                    'components'                  => 'required|array',
                    'components.*.component_type' => 'required|string',
                    'components.*.content'        => 'required|array',
                    'components.*.settings'       => 'nullable|array',
                    'components.*.sort_order'     => 'required|integer',
                    'components.*.is_visible'     => 'required|boolean',
                ]);

                $page->components()->delete();

                foreach ($validated['components'] as $componentData) {
                    $page->components()->create($componentData);
                }

                $page->update(['use_page_builder' => true]);

                return $this->successResponse('Komponen berhasil disimpan!');
            }

            // --- Format 2: Single component from admin form ---
            $validated = $request->validate([
                'component_type' => 'required|string|in:' . implode(',', array_keys($this->componentTypes)),
                'content'        => 'required|string',   // JSON string from textarea
                'sort_order'     => 'nullable|integer',
                'is_visible'     => 'nullable',
            ]);

            // Decode content JSON
            $contentDecoded = json_decode($validated['content'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()->withErrors(['content' => 'Format JSON tidak valid: ' . json_last_error_msg()]);
            }

            $page->components()->create([
                'component_type' => $validated['component_type'],
                'content'        => $contentDecoded,
                'settings'       => [],
                'sort_order'     => $validated['sort_order'] ?? ($page->components()->max('sort_order') + 1),
                'is_visible'     => $request->boolean('is_visible', true),
            ]);

            $page->update(['use_page_builder' => true]);

            return redirect()->route('admin.page-builder.edit', $page)
                ->with('success', 'Komponen berhasil ditambahkan!');

        } catch (\Throwable $e) {
            return $this->handleError($e, 'Gagal menyimpan komponen.');
        }
    }

    /**
     * Preview page with components
     */
    public function preview(Page $page): View
    {
        $page->load('visibleComponents');

        return view('admin.page-builder.preview', compact('page'));
    }

    /**
     * Delete a component
     */
    public function deleteComponent(PageComponent $component): JsonResponse
    {
        try {
            $component->delete();

            return $this->successResponse('Component deleted successfully!');
        } catch (\Throwable $e) {
            return $this->handleError($e, 'Failed to delete component.');
        }
    }
}
