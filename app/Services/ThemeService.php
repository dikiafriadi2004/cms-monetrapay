<?php

namespace App\Services;

use App\Models\Theme;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;

class ThemeService
{
    /**
     * Get the active theme
     */
    public function getActiveTheme(): ?Theme
    {
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
        
        return ":root {
            --theme-primary: {$config['primary_color']};
            --theme-secondary: {$config['secondary_color']};
            --theme-font-heading: {$config['font_heading']}, sans-serif;
            --theme-font-body: {$config['font_body']}, sans-serif;
        }";
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
