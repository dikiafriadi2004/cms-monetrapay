<?php

namespace App\Services;

use App\Models\Theme;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;

class ThemeService
{
    /**
     * Get the active theme (or preview theme if in preview mode)
     */
    public function getActiveTheme(): ?Theme
    {
        // Check if we're in preview mode
        if (request()->has('theme_preview') && request()->user()) {
            $previewId = request()->get('theme_preview');
            return Theme::find($previewId);
        }

        return Cache::remember('active_theme', 3600, function () {
            return Theme::active();
        });
    }

    /**
     * Generate CSS variables from theme config
     */
    public function applyThemeStyles(): string
    {
        $theme = $this->getActiveTheme();

        if (!$theme || !$theme->config) {
            return '';
        }

        $config = $theme->config;
        $primary   = $config['primary_color']   ?? '#3D9E7A';
        $secondary = $config['secondary_color']  ?? '#FF6B35';
        $fontHead  = $config['font_heading']     ?? 'Plus Jakarta Sans';
        $fontBody  = $config['font_body']        ?? 'Inter';

        // Per-theme extra CSS based on slug
        $extra = $this->getThemeExtraStyles($theme->slug, $primary, $secondary);

        return ":root {
            --theme-primary: {$primary};
            --theme-secondary: {$secondary};
            --theme-font-heading: '{$fontHead}', sans-serif;
            --theme-font-body: '{$fontBody}', sans-serif;
        }" . $extra;
    }

    /**
     * Return per-theme override CSS
     */
    protected function getThemeExtraStyles(string $slug, string $primary, string $secondary): string
    {
        return match ($slug) {
            'creative-agency' => "
                body { font-family: var(--theme-font-body); }
                h1,h2,h3,h4 { font-family: var(--theme-font-heading); letter-spacing:-0.03em; }
                .page-builder-content section { border-radius: 1.5rem; margin: 1rem; }
            ",
            'minimalist-blog' => "
                body { font-family: var(--theme-font-body); max-width: 52rem; margin: 0 auto; color:#1e293b; }
                h1,h2,h3 { font-family: var(--theme-font-heading); }
                p { line-height: 1.85; font-size: 1.0625rem; }
                .page-builder-content section { padding-top: 3rem; padding-bottom: 3rem; }
            ",
            'dark-mode-elegant' => "
                html, body { background: #0f0f0f !important; color: #e2e8f0 !important; }
                .bg-white { background: #1a1a2e !important; }
                .text-slate-700, .text-slate-800 { color: #e2e8f0 !important; }
                .border-slate-100, .border-slate-200 { border-color: #2d2d44 !important; }
                header { background: rgba(15,15,15,0.85) !important; backdrop-filter: blur(12px); border-bottom: 1px solid rgba(255,255,255,0.08) !important; }
            ",
            'tech-startup' => "
                h1,h2,h3,h4 { font-family: var(--theme-font-heading); }
                .page-builder-content h1, .page-builder-content h2 {
                    background: linear-gradient(135deg, {$primary}, {$secondary});
                    -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
                }
                section { position: relative; }
            ",
            default => "" // modern-corporate: clean defaults, no overrides needed
        };
    }

    /**
     * Get CSS body class for active theme
     */
    public function getBodyClass(): string
    {
        $theme = $this->getActiveTheme();
        return $theme ? 'theme-' . $theme->slug : 'theme-modern-corporate';
    }

    /**
     * Render a page builder component
     */
    public function renderComponent(string $type, array $content, array $settings = []): string
    {
        $viewPath = "components.builder.{$type}";
        
        if (!View::exists($viewPath)) {
            return "<!-- Component '{$type}' not found -->";
        }

        return view($viewPath, [
            'content' => $content,
            'settings' => $settings,
        ])->render();
    }

    /**
     * Clear theme cache
     */
    public function clearCache(): void
    {
        Cache::forget('active_theme');
    }

    /**
     * Get all themes
     */
    public function getAllThemes()
    {
        return Theme::orderBy('sort_order')->get();
    }

    /**
     * Activate a theme
     */
    public function activateTheme(int $themeId): bool
    {
        $theme = Theme::find($themeId);
        
        if (!$theme) {
            return false;
        }

        $theme->activate();
        $this->clearCache();
        
        return true;
    }
}
