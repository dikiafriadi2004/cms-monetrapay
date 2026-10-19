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
     * Return per-theme override CSS — each theme has distinct visual identity
     */
    protected function getThemeExtraStyles(string $slug, string $primary, string $secondary): string
    {
        return match ($slug) {

            // ── CREATIVE AGENCY ──────────────────────────────────────────────────
            // Big bold typography, colorful gradient accents, rounded sections
            'creative-agency' => "
                @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;900&family=Open+Sans:wght@400;600&display=swap');
                :root { --theme-radius: 2rem; }
                body { font-family: 'Open Sans', sans-serif; background: #fafafa; }
                h1,h2,h3,h4,h5 { font-family: 'Montserrat', sans-serif; font-weight: 900; letter-spacing: -0.04em; }
                header { border-bottom: none !important; box-shadow: none !important; background: white !important; border-bottom: 3px solid {$primary} !important; }
                .page-builder-content > section {
                    border-radius: 2rem;
                    margin: 0.75rem 1rem;
                    overflow: hidden;
                }
                .page-builder-content > section:first-child { margin-top: 0; border-radius: 0 0 2rem 2rem; }
                footer { background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%) !important; }
            ",

            // ── MINIMALIST BLOG ───────────────────────────────────────────────────
            // Narrow column, large serif typography, generous whitespace, no decorations
            'minimalist-blog' => "
                @import url('https://fonts.googleapis.com/css2?family=Lora:wght@400;600;700&family=Source+Sans+3:wght@300;400;600&display=swap');
                body {
                    font-family: 'Source Sans 3', sans-serif;
                    font-size: 1.1rem;
                    line-height: 1.9;
                    color: #2d2d2d;
                    background: #fffef9;
                }
                h1,h2,h3,h4,h5 { font-family: 'Lora', Georgia, serif; font-weight: 700; line-height: 1.3; color: #1a1a1a; }
                header {
                    background: #fffef9 !important;
                    border-bottom: 1px solid #e8e0d0 !important;
                    box-shadow: none !important;
                }
                header nav a { color: #4a4a4a !important; font-weight: 400; font-size: 0.9rem; letter-spacing: 0.05em; text-transform: uppercase; }
                .max-w-7xl { max-width: 72rem !important; }
                .page-builder-content section { background: #fffef9; }
                .page-builder-content section:nth-child(even) { background: #f7f3ec; }
                footer { background: #1a1a1a !important; }
            ",

            // ── E-COMMERCE STYLE ─────────────────────────────────────────────────
            // Clean product-focused, compact spacing, badge accents, green CTA
            'ecommerce-style' => "
                @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap');
                :root { --ec-green: {$primary}; --ec-orange: {$secondary}; }
                body { font-family: 'Roboto', sans-serif; font-size: 0.9375rem; background: #f5f5f5; }
                h1,h2,h3,h4,h5 { font-family: 'Roboto', sans-serif; font-weight: 700; }
                header {
                    background: white !important;
                    border-bottom: 2px solid {$primary} !important;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.08) !important;
                }
                header nav a { font-size: 0.8rem !important; text-transform: uppercase; letter-spacing: 0.08em; font-weight: 500; }
                .page-builder-content > section { background: white; margin-bottom: 0.5rem; }
                .page-builder-content > section:nth-child(even) { background: #f9fafb; }
                footer {
                    background: #1f2937 !important;
                    border-top: 4px solid {$secondary} !important;
                }
            ",

            // ── MAGAZINE LAYOUT ───────────────────────────────────────────────────
            // News-style, strong red accent, serif headings, editorial feel
            'magazine-layout' => "
                @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,700&family=Source+Sans+3:wght@300;400;600&display=swap');
                :root { --mag-red: {$primary}; --mag-dark: #1a1a1a; }
                body { font-family: 'Source Sans 3', 'Helvetica Neue', sans-serif; background: #fff; font-size: 1rem; line-height: 1.7; }
                h1,h2,h3 { font-family: 'Playfair Display', Georgia, serif; font-weight: 900; color: #1a1a1a; }
                h4,h5,h6 { font-family: 'Source Sans 3', sans-serif; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; font-size: 0.85rem; color: {$primary}; }
                header {
                    background: white !important;
                    border-top: 4px solid {$primary} !important;
                    border-bottom: 1px solid #e0e0e0 !important;
                    box-shadow: none !important;
                }
                header nav a { font-size: 0.8rem !important; font-weight: 700; text-transform: uppercase; letter-spacing: 0.12em; }
                .page-builder-content > section { border-bottom: 1px solid #f0f0f0; }
                footer { background: #1a1a1a !important; border-top: 4px solid {$primary} !important; }
            ",

            // ── DARK MODE ELEGANT ─────────────────────────────────────────────────
            // Full dark, glassmorphism cards, purple glow accents, luxury feel
            'dark-mode-elegant' => "
                @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Nunito:wght@300;400;600&display=swap');
                :root { --glow: {$primary}; }
                html, body {
                    background: #080810 !important;
                    color: #e2e8f0 !important;
                    font-family: 'Nunito', sans-serif;
                }
                h1,h2,h3,h4,h5 { font-family: 'Poppins', sans-serif; font-weight: 700; color: #f0f4ff !important; }
                header {
                    background: rgba(8,8,16,0.8) !important;
                    backdrop-filter: blur(16px) !important;
                    -webkit-backdrop-filter: blur(16px) !important;
                    border-bottom: 1px solid rgba(255,255,255,0.08) !important;
                    box-shadow: none !important;
                }
                header nav a { color: #cbd5e1 !important; }
                header nav a:hover { color: {$primary} !important; }
                .bg-white { background: #12121e !important; }
                .bg-slate-50 { background: #0e0e1a !important; }
                .text-slate-600, .text-slate-700 { color: #a0aec0 !important; }
                .text-slate-800 { color: #e2e8f0 !important; }
                .border-slate-100, .border-slate-200 { border-color: rgba(255,255,255,0.08) !important; }
                .shadow-sm { box-shadow: 0 0 0 1px rgba(255,255,255,0.06), 0 4px 24px rgba(0,0,0,0.4) !important; }
                section { background: transparent !important; }
                .page-builder-content section:nth-child(even) {
                    background: rgba(255,255,255,0.02) !important;
                }
                footer { background: #050508 !important; border-top: 1px solid rgba(255,255,255,0.06) !important; }
                footer .text-slate-400 { color: #6b7280 !important; }
                a:not([class]) { color: {$primary} !important; }
            ",

            // ── LANDING PAGE ──────────────────────────────────────────────────────
            // Bold gradient sections, conversion-focused, no sidebar clutter
            'landing-page' => "
                @import url('https://fonts.googleapis.com/css2?family=Raleway:wght@400;700;800;900&family=Open+Sans:wght@400;600&display=swap');
                body { font-family: 'Open Sans', sans-serif; background: #fff; }
                h1,h2,h3,h4,h5 { font-family: 'Raleway', sans-serif; font-weight: 900; }
                header {
                    background: transparent !important;
                    border-bottom: none !important;
                    box-shadow: none !important;
                    position: absolute !important;
                    width: 100% !important;
                    z-index: 50 !important;
                }
                header nav a { color: white !important; text-shadow: 0 1px 4px rgba(0,0,0,0.3); }
                header .text-slate-600 { color: rgba(255,255,255,0.85) !important; }
                header button { color: white !important; }
                .page-builder-content > section:first-child { padding-top: 8rem !important; }
                .page-builder-content > section:nth-child(even) {
                    background: linear-gradient(135deg, rgba({$primary}15,0.06) 0%, rgba({$secondary}15,0.06) 100%);
                }
                footer { background: #0f172a !important; }
            ",

            // ── PORTFOLIO SHOWCASE ────────────────────────────────────────────────
            // Black & gold, masonry feel, large image focus, dramatic typography
            'portfolio-showcase' => "
                @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@400;600;700&family=Roboto:wght@300;400&display=swap');
                :root { --gold: {$secondary}; }
                body { font-family: 'Roboto', sans-serif; font-weight: 300; background: #111; color: #e5e5e5; }
                h1,h2,h3,h4,h5 { font-family: 'Oswald', sans-serif; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #fff; }
                header {
                    background: #000 !important;
                    border-bottom: 1px solid rgba(255,215,0,0.2) !important;
                    box-shadow: none !important;
                }
                header nav a { color: #ccc !important; font-weight: 400; font-size: 0.8rem; letter-spacing: 0.12em; text-transform: uppercase; }
                header nav a:hover { color: {$secondary} !important; }
                .bg-white { background: #1a1a1a !important; }
                .bg-slate-50 { background: #111 !important; }
                .text-slate-700, .text-slate-800, .text-slate-600 { color: #d1d5db !important; }
                .border-slate-100, .border-slate-200 { border-color: rgba(255,255,255,0.08) !important; }
                section { background: #111 !important; }
                .page-builder-content section:nth-child(even) { background: #1a1a1a !important; }
                footer { background: #000 !important; border-top: 1px solid rgba(255,215,0,0.15) !important; }
            ",

            // ── TECH STARTUP ──────────────────────────────────────────────────────
            // Gradient text headings, glowing CTA, grid pattern bg, modern SaaS look
            'tech-startup' => "
                @import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap');
                body { font-family: 'Inter', sans-serif; background: #f8faff; }
                h1,h2,h3,h4,h5 {
                    font-family: 'Space Grotesk', sans-serif;
                    font-weight: 700;
                    background: linear-gradient(135deg, {$primary} 0%, {$secondary} 100%);
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                    background-clip: text;
                }
                header {
                    background: rgba(248,250,255,0.85) !important;
                    backdrop-filter: blur(12px) !important;
                    border-bottom: 1px solid rgba(102,126,234,0.15) !important;
                    box-shadow: 0 1px 24px rgba(102,126,234,0.08) !important;
                }
                .page-builder-content section:nth-child(odd) {
                    background: linear-gradient(135deg, rgba(102,126,234,0.04) 0%, rgba(118,75,162,0.04) 100%);
                }
                .page-builder-content section:nth-child(even) { background: #fff; }
                footer { background: #0f0f1a !important; }
                a[class*='bg-primary'], button[class*='bg-primary'] {
                    box-shadow: 0 4px 24px {$primary}66 !important;
                }
            ",

            // ── CLASSIC BUSINESS ──────────────────────────────────────────────────
            // Traditional serif, forest green & brown, authoritative, established look
            'classic-business' => "
                @import url('https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700;900&family=Lato:wght@300;400;700&display=swap');
                body { font-family: 'Lato', 'Times New Roman', serif; background: #fdfdf8; color: #333; line-height: 1.8; }
                h1,h2,h3,h4,h5 { font-family: 'Merriweather', Georgia, serif; font-weight: 900; color: {$primary}; line-height: 1.3; }
                header {
                    background: #fff !important;
                    border-top: 5px solid {$primary} !important;
                    border-bottom: 2px solid #e8e0d0 !important;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.06) !important;
                }
                header nav a { font-family: 'Lato', sans-serif; font-weight: 700; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.12em; }
                .page-builder-content section:nth-child(even) { background: #f5f2eb !important; }
                footer {
                    background: {$primary} !important;
                    border-top: 4px solid {$secondary} !important;
                }
                footer .text-slate-400, footer .text-white { color: rgba(255,255,255,0.75) !important; }
            ",

            // ── MODERN CORPORATE (default — no overrides needed) ──────────────────
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
