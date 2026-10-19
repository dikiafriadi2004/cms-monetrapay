<?php

namespace App\Services;

use App\Models\Theme;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;

class ThemeService
{
    /**
     * Get the active theme (or preview theme if in preview mode).
     * Preview is identified by a signed token in ?theme_preview=id&_token=...
     * so it works inside iframes without session/auth.
     */
    public function getActiveTheme(): ?Theme
    {
        $request = request();

        if ($request->has('theme_preview')) {
            $id    = (int) $request->get('theme_preview');
            $token = $request->get('_preview_token', '');

            // Validate HMAC token: sha256(id . app_key)
            $expected = substr(hash_hmac('sha256', (string)$id, config('app.key')), 0, 16);

            if (hash_equals($expected, $token)) {
                $theme = Theme::find($id);
                if ($theme) {
                    return $theme;
                }
            }
        }

        return Cache::remember('active_theme', 3600, function () {
            return Theme::active();
        });
    }

    /**
     * Generate a signed preview URL for a theme.
     */
    public function previewUrl(Theme $theme, string $path = '/'): string
    {
        $token = substr(hash_hmac('sha256', (string)$theme->id, config('app.key')), 0, 16);
        $separator = str_contains($path, '?') ? '&' : '?';
        return url($path) . $separator . 'theme_preview=' . $theme->id . '&_preview_token=' . $token;
    }

    /**
     * Get the layout view name for the active theme.
     * If a theme-specific layout exists, use it; otherwise fall back to default.
     */
    public function getLayoutView(): string
    {
        $theme = $this->getActiveTheme();
        if (!$theme) {
            return 'frontend.layouts.app';
        }
        $themed = 'frontend.themes.' . $theme->slug . '.layout';
        return View::exists($themed) ? $themed : 'frontend.layouts.app';
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
     * Return per-theme override CSS — strong !important overrides for all themes
     */
    protected function getThemeExtraStyles(string $slug, string $primary, string $secondary): string
    {
        return match ($slug) {

            'creative-agency' => "
                @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;900&family=Open+Sans:wght@400;600&display=swap');
                body{font-family:'Open Sans',sans-serif!important;background:#fafafa!important}
                h1,h2,h3,h4,h5{font-family:'Montserrat',sans-serif!important;font-weight:900!important;letter-spacing:-0.04em!important}
                header{border-bottom:3px solid {$primary}!important;box-shadow:none!important;background:white!important}
                header nav a{border-radius:999px!important;font-weight:700!important}
                header nav a:hover{background:rgba(255,107,107,0.12)!important;color:{$primary}!important}
                section{border-radius:1.5rem!important;overflow:hidden!important;margin-bottom:0.5rem!important}
                footer,footer .bg-dark,.bg-dark{background:linear-gradient(135deg,#1a1a2e,#16213e)!important}
            ",

            'minimalist-blog' => "
                @import url('https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,700;1,400&family=Source+Sans+3:wght@300;400;600&display=swap');
                body{font-family:'Source Sans 3',sans-serif!important;font-size:1.0625rem!important;line-height:1.85!important;background:#fffef9!important;color:#2d2d2d!important}
                h1,h2,h3,h4,h5{font-family:'Lora',Georgia,serif!important;font-weight:700!important;color:#1a1a1a!important}
                header{background:#fffef9!important;border-bottom:1px solid #e8e0d0!important;box-shadow:none!important}
                header nav a{font-size:0.875rem!important;color:#555!important;font-weight:400!important}
                header nav a:hover{color:{$primary}!important;background:transparent!important;box-shadow:none!important}
                main>.max-w-7xl,main .max-w-7xl{max-width:46rem!important}
                section{background:#fffef9!important}
                section:nth-child(even){background:#f7f3ec!important}
                footer,footer .bg-dark,.bg-dark{background:#1a1a1a!important}
            ",

            'ecommerce-style' => "
                @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap');
                body{font-family:'Roboto',sans-serif!important;font-size:0.9375rem!important;background:#f3f4f6!important}
                h1,h2,h3,h4,h5{font-family:'Roboto',sans-serif!important;font-weight:700!important}
                header{background:white!important;border-bottom:2px solid {$primary}!important;box-shadow:0 2px 8px rgba(0,0,0,0.08)!important}
                header nav a{font-size:0.75rem!important;text-transform:uppercase!important;letter-spacing:0.08em!important;font-weight:500!important}
                section{background:white!important;margin-bottom:0.25rem!important}
                section:nth-child(even){background:#f9fafb!important}
                .bg-primary{background:{$primary}!important}
                .bg-secondary{background:{$secondary}!important}
                footer,footer .bg-dark,.bg-dark{background:#1f2937!important;border-top:4px solid {$secondary}!important}
            ",

            'magazine-layout' => "
                @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Source+Sans+3:wght@300;400;600;700&display=swap');
                body{font-family:'Source Sans 3',sans-serif!important;background:white!important;color:#1a1a1a!important;line-height:1.7!important}
                h1,h2,h3{font-family:'Playfair Display',Georgia,serif!important;font-weight:900!important;color:#1a1a1a!important}
                h4,h5,h6{font-family:'Source Sans 3',sans-serif!important;font-weight:700!important;text-transform:uppercase!important;letter-spacing:0.1em!important;font-size:0.8rem!important;color:{$primary}!important}
                header{background:white!important;border-top:4px solid {$primary}!important;border-bottom:1px solid #e0e0e0!important;box-shadow:none!important}
                header nav a{font-size:0.75rem!important;font-weight:700!important;text-transform:uppercase!important;letter-spacing:0.12em!important}
                .bg-primary{background:{$primary}!important}
                .bg-secondary{background:{$secondary}!important}
                footer,footer .bg-dark,.bg-dark{background:#1a1a1a!important;border-top:4px solid {$primary}!important}
            ",

            'dark-mode-elegant' => "
                @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Nunito:wght@300;400;600&display=swap');
                html,body{background:#080810!important;color:#e2e8f0!important}
                body{font-family:'Nunito',sans-serif!important}
                h1,h2,h3,h4,h5{font-family:'Poppins',sans-serif!important;font-weight:700!important;color:#f0f4ff!important}
                header{background:rgba(8,8,16,0.9)!important;backdrop-filter:blur(16px)!important;-webkit-backdrop-filter:blur(16px)!important;border-bottom:1px solid rgba(255,255,255,0.08)!important;box-shadow:none!important}
                header nav a{color:rgba(226,232,240,0.75)!important}
                header nav a:hover{color:{$primary}!important;background:rgba(159,122,234,0.1)!important}
                .bg-white{background:#12121e!important}
                .bg-slate-50{background:#0e0e1a!important}
                .text-slate-700,.text-slate-800,.text-slate-600{color:#a0aec0!important}
                .border-slate-100,.border-slate-200{border-color:rgba(255,255,255,0.08)!important}
                .shadow-sm{box-shadow:0 0 0 1px rgba(255,255,255,0.06),0 4px 24px rgba(0,0,0,0.4)!important}
                section{background:#080810!important}
                section:nth-child(even){background:#0e0e1a!important}
                .bg-primary{background:{$primary}!important}
                .text-primary{color:{$primary}!important}
                .bg-secondary{background:{$secondary}!important}
                footer,footer .bg-dark,.bg-dark{background:#050508!important;border-top:1px solid rgba(255,255,255,0.06)!important}
                footer .text-slate-400{color:#6b7280!important}
            ",

            'landing-page' => "
                @import url('https://fonts.googleapis.com/css2?family=Raleway:wght@400;700;800;900&family=Open+Sans:wght@400;600&display=swap');
                body{font-family:'Open Sans',sans-serif!important;background:#fff!important}
                h1,h2,h3,h4,h5{font-family:'Raleway',sans-serif!important;font-weight:900!important}
                header{background:transparent!important;border-bottom:none!important;box-shadow:none!important;position:fixed!important;width:100%!important;z-index:50!important}
                header nav a{color:rgba(255,255,255,0.9)!important;text-shadow:0 1px 4px rgba(0,0,0,0.4)!important}
                header .text-slate-600,header .text-slate-500{color:rgba(255,255,255,0.85)!important}
                header button svg{stroke:white!important}
                main{padding-top:4rem!important}
                .bg-primary{background:{$primary}!important}
                .bg-secondary{background:{$secondary}!important}
                footer,footer .bg-dark,.bg-dark{background:#0f172a!important}
            ",

            'portfolio-showcase' => "
                @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@400;600;700&family=Roboto:wght@300;400&display=swap');
                html,body{background:#111!important;color:#e5e5e5!important}
                body{font-family:'Roboto',sans-serif!important;font-weight:300!important}
                h1,h2,h3,h4,h5{font-family:'Oswald',sans-serif!important;font-weight:700!important;text-transform:uppercase!important;letter-spacing:0.05em!important;color:white!important}
                header{background:#000!important;border-bottom:1px solid rgba(255,215,0,0.2)!important;box-shadow:none!important}
                header nav a{color:#bbb!important;font-size:0.75rem!important;letter-spacing:0.12em!important;text-transform:uppercase!important;font-weight:400!important}
                header nav a:hover{color:{$secondary}!important;background:transparent!important}
                .bg-white{background:#1a1a1a!important}
                .bg-slate-50{background:#111!important}
                .text-slate-700,.text-slate-800,.text-slate-600{color:#d1d5db!important}
                .border-slate-100,.border-slate-200{border-color:rgba(255,255,255,0.08)!important}
                section{background:#111!important}
                section:nth-child(even){background:#1a1a1a!important}
                .bg-primary{background:#1a1a1a!important}
                .text-primary{color:{$secondary}!important}
                .bg-secondary{background:{$secondary}!important}
                footer,footer .bg-dark,.bg-dark{background:#000!important;border-top:1px solid rgba(255,215,0,0.15)!important}
            ",

            'tech-startup' => "
                @import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap');
                body{font-family:'Inter',sans-serif!important;background:#f8faff!important}
                h1,h2,h3,h4,h5{font-family:'Space Grotesk',sans-serif!important;font-weight:700!important;background:linear-gradient(135deg,{$primary},{$secondary})!important;-webkit-background-clip:text!important;-webkit-text-fill-color:transparent!important;background-clip:text!important}
                header{background:rgba(248,250,255,0.9)!important;backdrop-filter:blur(12px)!important;border-bottom:2px solid transparent!important;border-image:linear-gradient(90deg,{$primary},{$secondary}) 1!important;box-shadow:0 1px 24px rgba(102,126,234,0.1)!important}
                section:nth-child(odd){background:linear-gradient(135deg,rgba(102,126,234,0.04),rgba(118,75,162,0.04))!important}
                section:nth-child(even){background:white!important}
                .bg-primary{background:{$primary}!important}
                .bg-secondary{background:{$secondary}!important}
                footer,footer .bg-dark,.bg-dark{background:#0f0f1a!important}
            ",

            'classic-business' => "
                @import url('https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700;900&family=Lato:wght@300;400;700&display=swap');
                body{font-family:'Lato',sans-serif!important;background:#fdfdf8!important;color:#333!important;line-height:1.8!important}
                h1,h2,h3,h4,h5{font-family:'Merriweather',Georgia,serif!important;font-weight:900!important;color:{$primary}!important;line-height:1.3!important}
                header{background:white!important;border-top:5px solid {$primary}!important;border-bottom:2px solid #e8e0d0!important;box-shadow:0 2px 8px rgba(0,0,0,0.06)!important}
                header nav a{font-family:'Lato',sans-serif!important;font-weight:700!important;font-size:0.75rem!important;text-transform:uppercase!important;letter-spacing:0.12em!important}
                section:nth-child(even){background:#f5f2eb!important}
                .bg-primary{background:{$primary}!important}
                .bg-secondary{background:{$secondary}!important}
                .text-primary{color:{$primary}!important}
                footer,footer .bg-dark,.bg-dark{background:{$primary}!important;border-top:4px solid {$secondary}!important}
                footer .text-slate-400{color:rgba(255,255,255,0.6)!important}
            ",

            default => "",
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
