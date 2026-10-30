@php
    /**
     * Resolves the frontend layout name.
     * Themes feature removed — always use the default layout.
     */
    if (!isset($themeLayout)) {
        $themeLayout = 'frontend.layouts.app';
    }
@endphp
