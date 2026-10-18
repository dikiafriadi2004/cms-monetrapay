<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageComponent;
use App\Traits\RespondsWithError;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageBuilderController extends Controller
{
    use RespondsWithError;

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
     * Show page builder editor
     */
    public function edit(Page $page): View
    {
        $this->authorize('page.edit');

        $page->load('components');

        return view('admin.page-builder.edit', compact('page'));
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
     * Save page components
     */
    public function save(Request $request, Page $page): JsonResponse
    {
        $this->authorize('page.edit');

        $validated = $request->validate([
            'components' => 'required|array',
            'components.*.component_type' => 'required|string',
            'components.*.content' => 'required|array',
            'components.*.settings' => 'nullable|array',
            'components.*.sort_order' => 'required|integer',
            'components.*.is_visible' => 'required|boolean',
        ]);

        try {
            // Delete existing components
            $page->components()->delete();

            // Create new components
            foreach ($validated['components'] as $componentData) {
                $page->components()->create($componentData);
            }

            // Enable page builder for this page
            $page->update(['use_page_builder' => true]);

            return $this->successResponse('Page components saved successfully!');
        } catch (\Throwable $e) {
            return $this->handleError($e, 'Failed to save page components.');
        }
    }

    /**
     * Preview page with components
     */
    public function preview(Page $page): View
    {
        $this->authorize('page.view');

        $page->load('visibleComponents');

        return view('admin.page-builder.preview', compact('page'));
    }

    /**
     * Delete a component
     */
    public function deleteComponent(PageComponent $component): JsonResponse
    {
        $this->authorize('page.edit');

        try {
            $component->delete();

            return $this->successResponse('Component deleted successfully!');
        } catch (\Throwable $e) {
            return $this->handleError($e, 'Failed to delete component.');
        }
    }
}
