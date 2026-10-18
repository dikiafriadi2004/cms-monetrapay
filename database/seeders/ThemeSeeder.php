<?php

namespace Database\Seeders;

use App\Models\Theme;
use Illuminate\Database\Seeder;

class ThemeSeeder extends Seeder
{
    public function run(): void
    {
        $themes = [
            [
                'name' => 'Modern Corporate',
                'slug' => 'modern-corporate',
                'description' => 'Clean and professional design with blue accent colors. Perfect for business and corporate websites.',
                'thumbnail' => '/assets/themes/modern-corporate.jpg',
                'config' => [
                    'primary_color' => '#3D9E7A',
                    'secondary_color' => '#FF6B35',
                    'font_heading' => 'Plus Jakarta Sans',
                    'font_body' => 'Inter',
                    'header_style' => 'fixed',
                    'footer_style' => 'dark',
                    'sidebar_position' => 'right',
                    'layout_width' => 'boxed',
                ],
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Creative Agency',
                'slug' => 'creative-agency',
                'description' => 'Bold and colorful with gradient accents. Ideal for creative agencies and design studios.',
                'thumbnail' => '/assets/themes/creative-agency.jpg',
                'config' => [
                    'primary_color' => '#FF6B6B',
                    'secondary_color' => '#4ECDC4',
                    'font_heading' => 'Montserrat',
                    'font_body' => 'Open Sans',
                    'header_style' => 'transparent',
                    'footer_style' => 'gradient',
                    'sidebar_position' => 'none',
                    'layout_width' => 'full',
                ],
                'is_active' => false,
                'sort_order' => 2,
            ],
            [
                'name' => 'Minimalist Blog',
                'slug' => 'minimalist-blog',
                'description' => 'Simple and clean with focus on typography. Great for blogs and personal websites.',
                'thumbnail' => '/assets/themes/minimalist-blog.jpg',
                'config' => [
                    'primary_color' => '#2C3E50',
                    'secondary_color' => '#E74C3C',
                    'font_heading' => 'Merriweather',
                    'font_body' => 'Lato',
                    'header_style' => 'minimal',
                    'footer_style' => 'simple',
                    'sidebar_position' => 'right',
                    'layout_width' => 'narrow',
                ],
                'is_active' => false,
                'sort_order' => 3,
            ],
            [
                'name' => 'E-commerce Style',
                'slug' => 'ecommerce-style',
                'description' => 'Product-focused grid layout. Optimized for online stores and catalogs.',
                'thumbnail' => '/assets/themes/ecommerce-style.jpg',
                'config' => [
                    'primary_color' => '#27AE60',
                    'secondary_color' => '#F39C12',
                    'font_heading' => 'Roboto',
                    'font_body' => 'Roboto',
                    'header_style' => 'sticky',
                    'footer_style' => 'mega',
                    'sidebar_position' => 'left',
                    'layout_width' => 'wide',
                ],
                'is_active' => false,
                'sort_order' => 4,
            ],
            [
                'name' => 'Magazine Layout',
                'slug' => 'magazine-layout',
                'description' => 'Multi-column news-style layout. Perfect for news portals and content-heavy sites.',
                'thumbnail' => '/assets/themes/magazine-layout.jpg',
                'config' => [
                    'primary_color' => '#C0392B',
                    'secondary_color' => '#34495E',
                    'font_heading' => 'Playfair Display',
                    'font_body' => 'Source Sans Pro',
                    'header_style' => 'full',
                    'footer_style' => 'widget',
                    'sidebar_position' => 'both',
                    'layout_width' => 'full',
                ],
                'is_active' => false,
                'sort_order' => 5,
            ],
            [
                'name' => 'Dark Mode Elegant',
                'slug' => 'dark-mode-elegant',
                'description' => 'Modern dark theme with elegant accents. Eye-friendly and stylish.',
                'thumbnail' => '/assets/themes/dark-mode-elegant.jpg',
                'config' => [
                    'primary_color' => '#8E44AD',
                    'secondary_color' => '#3498DB',
                    'font_heading' => 'Poppins',
                    'font_body' => 'Nunito',
                    'header_style' => 'dark',
                    'footer_style' => 'dark',
                    'sidebar_position' => 'none',
                    'layout_width' => 'boxed',
                ],
                'is_active' => false,
                'sort_order' => 6,
            ],
            [
                'name' => 'Landing Page',
                'slug' => 'landing-page',
                'description' => 'Single-page conversion-focused design. Ideal for product launches and campaigns.',
                'thumbnail' => '/assets/themes/landing-page.jpg',
                'config' => [
                    'primary_color' => '#FF5722',
                    'secondary_color' => '#009688',
                    'font_heading' => 'Raleway',
                    'font_body' => 'Open Sans',
                    'header_style' => 'transparent',
                    'footer_style' => 'minimal',
                    'sidebar_position' => 'none',
                    'layout_width' => 'full',
                ],
                'is_active' => false,
                'sort_order' => 7,
            ],
            [
                'name' => 'Portfolio Showcase',
                'slug' => 'portfolio-showcase',
                'description' => 'Image-heavy masonry layout. Perfect for photographers and designers.',
                'thumbnail' => '/assets/themes/portfolio-showcase.jpg',
                'config' => [
                    'primary_color' => '#000000',
                    'secondary_color' => '#FFD700',
                    'font_heading' => 'Oswald',
                    'font_body' => 'Roboto',
                    'header_style' => 'overlay',
                    'footer_style' => 'minimal',
                    'sidebar_position' => 'none',
                    'layout_width' => 'full',
                ],
                'is_active' => false,
                'sort_order' => 8,
            ],
            [
                'name' => 'Tech Startup',
                'slug' => 'tech-startup',
                'description' => 'Modern gradient design with animations. Great for tech companies and startups.',
                'thumbnail' => '/assets/themes/tech-startup.jpg',
                'config' => [
                    'primary_color' => '#667EEA',
                    'secondary_color' => '#764BA2',
                    'font_heading' => 'Space Grotesk',
                    'font_body' => 'Inter',
                    'header_style' => 'gradient',
                    'footer_style' => 'gradient',
                    'sidebar_position' => 'none',
                    'layout_width' => 'wide',
                ],
                'is_active' => false,
                'sort_order' => 9,
            ],
            [
                'name' => 'Classic Business',
                'slug' => 'classic-business',
                'description' => 'Traditional serif fonts with professional layout. Timeless design for established businesses.',
                'thumbnail' => '/assets/themes/classic-business.jpg',
                'config' => [
                    'primary_color' => '#1A4D2E',
                    'secondary_color' => '#8B4513',
                    'font_heading' => 'Georgia',
                    'font_body' => 'Times New Roman',
                    'header_style' => 'traditional',
                    'footer_style' => 'classic',
                    'sidebar_position' => 'right',
                    'layout_width' => 'boxed',
                ],
                'is_active' => false,
                'sort_order' => 10,
            ],
        ];

        foreach ($themes as $theme) {
            Theme::create($theme);
        }
    }
}
