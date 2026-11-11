<?php

namespace App\View\Composers;

use App\Models\Menu;
use App\Models\Setting;
use App\Services\MenuService;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class FrontendComposer
{
    public function __construct(
        protected MenuService $menuService,
    ) {}

    /**
     * Bind data to the frontend layout view.
     */
    public function compose(View $view): void
    {
        try {
            // Site name
            $siteName = Setting::get('site_name', 'general', config('app.name'));

            // Google Analytics ID
            $gaId = Setting::get('ga_measurement_id', 'seo');

            // Header menu
            $headerMenu = $this->getMenuByLocation('header');

            // Footer menu
            $footerMenu = $this->getMenuByLocation('footer');

            // Logo
            $logo = Setting::get('logo', 'general');

            // Favicon
            $favicon = Setting::get('favicon', 'general');

            // Footer settings
            $footerSettings = [
                'site_name' => $siteName,
                'tagline' => Setting::get('tagline', 'general'),
                'contact_email' => Setting::get('contact_email', 'general'),
                'contact_phone' => Setting::get('contact_phone', 'general'),
                'copyright_text' => Setting::get('copyright_text', 'footer'),
                'footer_extra_content' => Setting::get('additional_content', 'footer'),
            ];

            // Social media links
            $socialLinks = Setting::getGroup('social');
        } catch (\Exception $e) {
            $siteName = config('app.name');
            $logo = null;
            $favicon = null;
            $gaId = null;
            $headerMenu = [];
            $footerMenu = [];
            $footerSettings = ['site_name' => config('app.name')];
            $socialLinks = [];
        }

        $view->with(compact(
            'siteName',
            'logo',
            'favicon',
            'gaId',
            'headerMenu',
            'footerMenu',
            'footerSettings',
            'socialLinks',
        ));
    }

    /**
     * Get menu items for a given location (cached).
     */
    protected function getMenuByLocation(string $location): array
    {
        return Cache::remember(
            'menu.location.'.$location,
            3600,
            function () use ($location) {
                $menu = Menu::where('location', $location)
                    ->where('is_active', true)
                    ->first();

                if (! $menu) {
                    return [];
                }

                return $this->menuService->buildTree($menu->id);
            }
        );
    }
}
