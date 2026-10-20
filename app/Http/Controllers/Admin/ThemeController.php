<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Theme;
use App\Services\ThemeService;
use App\Traits\RespondsWithError;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ThemeController extends Controller
{
    use RespondsWithError, AuthorizesRequests;

    public function __construct(
        protected ThemeService $themeService
    ) {}

    /**
     * Display all themes
     */
    public function index(): View
    {
        $themes = $this->themeService->getAllThemes();
        $activeTheme = $this->themeService->getActiveTheme();

        return view('admin.themes.index', compact('themes', 'activeTheme'));
    }

    /**
     * Show theme preview
     */
    public function preview(Theme $theme): View
    {
        $themeService = $this->themeService;
        $previewUrl   = $themeService->previewUrl($theme, '/');
        return view('admin.themes.preview', compact('theme', 'previewUrl'));
    }

    /**
     * Activate a theme
     */
    public function activate(Request $request, Theme $theme): JsonResponse|RedirectResponse
    {
        try {
            $this->themeService->activateTheme($theme->id);

            return $this->successResponse(
                'Theme "' . $theme->name . '" berhasil diaktifkan!',
                ['theme' => $theme]
            );
        } catch (\Throwable $e) {
            return $this->handleError($e, 'Gagal mengaktifkan theme.');
        }
    }

    /**
     * Show theme customization page
     */
    public function customize(Theme $theme): View
    {
        return view('admin.themes.customize', compact('theme'));
    }

    /**
     * Update theme configuration
     */
    public function update(Request $request, Theme $theme): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'config'                  => 'required|array',
            'config.primary_color'    => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'config.secondary_color'  => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'config.font_heading'     => 'required|string|max:100',
            'config.font_body'        => 'required|string|max:100',
            // Optional fields — not all themes use these
            'config.header_style'     => 'nullable|string|max:50',
            'config.footer_style'     => 'nullable|string|max:50',
            'config.sidebar_position' => 'nullable|string|max:20',
            'config.layout_width'     => 'nullable|string|max:20',
            // Extra custom fields
            'config.border_radius'    => 'nullable|string|max:20',
            'config.button_style'     => 'nullable|string|max:20',
        ], [
            'config.primary_color.regex'   => 'Primary color harus berformat hex (#RRGGBB).',
            'config.secondary_color.regex' => 'Secondary color harus berformat hex (#RRGGBB).',
        ]);

        // Merge incoming config with existing (don't overwrite fields not in form)
        $currentConfig  = $theme->config ?? [];
        $newConfig      = array_merge($currentConfig, $validated['config']);

        try {
            $theme->update(['config' => $newConfig]);
            $this->themeService->clearCache();

            if ($request->wantsJson() || $request->ajax()) {
                return $this->successResponse(
                    'Konfigurasi tema "' . $theme->name . '" berhasil disimpan!',
                    ['theme' => $theme->fresh()]
                );
            }

            return redirect()
                ->route('admin.themes.customize', $theme)
                ->with('success', 'Konfigurasi tema berhasil disimpan!');
        } catch (\Throwable $e) {
            return $this->handleError($e, 'Gagal menyimpan konfigurasi tema.');
        }
    }
}
