<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Theme;
use App\Services\ThemeService;
use App\Traits\RespondsWithError;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ThemeController extends Controller
{
    use RespondsWithError;

    public function __construct(
        protected ThemeService $themeService
    ) {}

    /**
     * Display all themes
     */
    public function index(): View
    {
        $this->authorize('setting.view');

        $themes = $this->themeService->getAllThemes();
        $activeTheme = $this->themeService->getActiveTheme();

        return view('admin.themes.index', compact('themes', 'activeTheme'));
    }

    /**
     * Show theme preview
     */
    public function preview(Theme $theme): View
    {
        $this->authorize('setting.view');

        return view('admin.themes.preview', compact('theme'));
    }

    /**
     * Activate a theme
     */
    public function activate(Request $request, Theme $theme): JsonResponse|RedirectResponse
    {
        $this->authorize('setting.edit');

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
        $this->authorize('setting.edit');

        return view('admin.themes.customize', compact('theme'));
    }

    /**
     * Update theme configuration
     */
    public function update(Request $request, Theme $theme): JsonResponse|RedirectResponse
    {
        $this->authorize('setting.edit');

        $validated = $request->validate([
            'config' => 'required|array',
            'config.primary_color' => 'required|string',
            'config.secondary_color' => 'required|string',
            'config.font_heading' => 'required|string',
            'config.font_body' => 'required|string',
            'config.header_style' => 'required|string',
            'config.footer_style' => 'required|string',
            'config.sidebar_position' => 'required|string',
            'config.layout_width' => 'required|string',
        ]);

        try {
            $theme->update(['config' => $validated['config']]);
            $this->themeService->clearCache();

            return $this->successResponse(
                'Konfigurasi theme berhasil disimpan!',
                ['theme' => $theme]
            );
        } catch (\Throwable $e) {
            return $this->handleError($e, 'Gagal menyimpan konfigurasi theme.');
        }
    }
}
