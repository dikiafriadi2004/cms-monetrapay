@php
    /**
     * This file resolves the active theme layout name.
     * Include at the top of every frontend view:
     *   @include('frontend.theme-layout')
     *   @extends($themeLayout)
     *
     * Falls back to frontend.layouts.app if no theme-specific layout exists.
     */
    if (!isset($themeLayout)) {
        try {
            $themeLayout = app(\App\Services\ThemeService::class)->getLayoutView();
        } catch (\Throwable $e) {
            $themeLayout = 'frontend.layouts.app';
        }
    }
@endphp
