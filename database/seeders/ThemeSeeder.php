<?php

namespace Database\Seeders;

use App\Models\Theme;
use Illuminate\Database\Seeder;

class ThemeSeeder extends Seeder
{
    public function run(): void
    {
        // Truncate and re-seed with updated configs
        Theme::truncate();

        $themes = [
            // 1. Modern Corporate — aktif default
            [
                'name'        => 'Modern Corporate',
                'slug'        => 'modern-corporate',
                'description' => 'Clean professional design dengan aksen hijau. Cocok untuk website bisnis dan korporat.',
                'thumbnail'   => null,
                'config'      => [
                    'primary_color'   => '#3D9E7A',
                    'secondary_color' => '#FF6B35',
                    'font_heading'    => 'Plus Jakarta Sans',
                    'font_body'       => 'Inter',
                    'header_style'    => 'sticky',
                    'footer_style'    => 'dark',
                    'layout_width'    => 'boxed',
                ],
                'is_active'   => true,
                'sort_order'  => 1,
            ],
            // 2. Creative Agency
            [
                'name'        => 'Creative Agency',
                'slug'        => 'creative-agency',
                'description' => 'Bold colorful dengan gradient aksen. Ideal untuk agensi kreatif dan studio desain.',
                'thumbnail'   => null,
                'config'      => [
                    'primary_color'   => '#FF6B6B',
                    'secondary_color' => '#4ECDC4',
                    'font_heading'    => 'Montserrat',
                    'font_body'       => 'Open Sans',
                    'header_style'    => 'border-bottom-accent',
                    'footer_style'    => 'gradient',
                    'layout_width'    => 'full',
                ],
                'is_active'   => false,
                'sort_order'  => 2,
            ],
            // 3. Minimalist Blog
            [
                'name'        => 'Minimalist Blog',
                'slug'        => 'minimalist-blog',
                'description' => 'Tipografi serif elegan, whitespace luas, fokus pada konten. Untuk blog dan personal site.',
                'thumbnail'   => null,
                'config'      => [
                    'primary_color'   => '#2C3E50',
                    'secondary_color' => '#E74C3C',
                    'font_heading'    => 'Lora',
                    'font_body'       => 'Source Sans 3',
                    'header_style'    => 'minimal',
                    'footer_style'    => 'dark',
                    'layout_width'    => 'narrow',
                ],
                'is_active'   => false,
                'sort_order'  => 3,
            ],
            // 4. E-commerce Style
            [
                'name'        => 'E-commerce Style',
                'slug'        => 'ecommerce-style',
                'description' => 'Layout product-focused, kompak, badge aksen. Untuk toko online dan katalog.',
                'thumbnail'   => null,
                'config'      => [
                    'primary_color'   => '#27AE60',
                    'secondary_color' => '#F39C12',
                    'font_heading'    => 'Roboto',
                    'font_body'       => 'Roboto',
                    'header_style'    => 'accent-border',
                    'footer_style'    => 'accent-border',
                    'layout_width'    => 'wide',
                ],
                'is_active'   => false,
                'sort_order'  => 4,
            ],
            // 5. Magazine Layout
            [
                'name'        => 'Magazine Layout',
                'slug'        => 'magazine-layout',
                'description' => 'Editorial news-style, serif heading Playfair Display, aksen merah kuat.',
                'thumbnail'   => null,
                'config'      => [
                    'primary_color'   => '#C0392B',
                    'secondary_color' => '#2C3E50',
                    'font_heading'    => 'Playfair Display',
                    'font_body'       => 'Source Sans 3',
                    'header_style'    => 'top-accent',
                    'footer_style'    => 'dark-accent',
                    'layout_width'    => 'full',
                ],
                'is_active'   => false,
                'sort_order'  => 5,
            ],
            // 6. Dark Mode Elegant
            [
                'name'        => 'Dark Mode Elegant',
                'slug'        => 'dark-mode-elegant',
                'description' => 'Full dark background, glassmorphism cards, purple glow. Modern dan mewah.',
                'thumbnail'   => null,
                'config'      => [
                    'primary_color'   => '#9F7AEA',
                    'secondary_color' => '#63B3ED',
                    'font_heading'    => 'Poppins',
                    'font_body'       => 'Nunito',
                    'header_style'    => 'glassmorphism',
                    'footer_style'    => 'ultra-dark',
                    'layout_width'    => 'wide',
                ],
                'is_active'   => false,
                'sort_order'  => 6,
            ],
            // 7. Landing Page
            [
                'name'        => 'Landing Page',
                'slug'        => 'landing-page',
                'description' => 'Transparent navbar overlay, gradient sections, konversi-focused. Untuk campaign.',
                'thumbnail'   => null,
                'config'      => [
                    'primary_color'   => '#FF5722',
                    'secondary_color' => '#00BCD4',
                    'font_heading'    => 'Raleway',
                    'font_body'       => 'Open Sans',
                    'header_style'    => 'transparent-overlay',
                    'footer_style'    => 'dark',
                    'layout_width'    => 'full',
                ],
                'is_active'   => false,
                'sort_order'  => 7,
            ],
            // 8. Portfolio Showcase
            [
                'name'        => 'Portfolio Showcase',
                'slug'        => 'portfolio-showcase',
                'description' => 'Dark background hitam, aksen emas, tipografi Oswald bold. Untuk fotografer & desainer.',
                'thumbnail'   => null,
                'config'      => [
                    'primary_color'   => '#1a1a1a',
                    'secondary_color' => '#FFD700',
                    'font_heading'    => 'Oswald',
                    'font_body'       => 'Roboto',
                    'header_style'    => 'dark',
                    'footer_style'    => 'black',
                    'layout_width'    => 'full',
                ],
                'is_active'   => false,
                'sort_order'  => 8,
            ],
            // 9. Tech Startup
            [
                'name'        => 'Tech Startup',
                'slug'        => 'tech-startup',
                'description' => 'Gradient heading text, glassmorphism navbar, SaaS modern look. Untuk tech company.',
                'thumbnail'   => null,
                'config'      => [
                    'primary_color'   => '#667EEA',
                    'secondary_color' => '#764BA2',
                    'font_heading'    => 'Space Grotesk',
                    'font_body'       => 'Inter',
                    'header_style'    => 'glassmorphism-light',
                    'footer_style'    => 'ultra-dark',
                    'layout_width'    => 'wide',
                ],
                'is_active'   => false,
                'sort_order'  => 9,
            ],
            // 10. Classic Business
            [
                'name'        => 'Classic Business',
                'slug'        => 'classic-business',
                'description' => 'Serif Merriweather, hijau forest, top border tebal. Timeless untuk bisnis established.',
                'thumbnail'   => null,
                'config'      => [
                    'primary_color'   => '#1A5C38',
                    'secondary_color' => '#8B4513',
                    'font_heading'    => 'Merriweather',
                    'font_body'       => 'Lato',
                    'header_style'    => 'top-border',
                    'footer_style'    => 'primary-bg',
                    'layout_width'    => 'boxed',
                ],
                'is_active'   => false,
                'sort_order'  => 10,
            ],
        ];

        foreach ($themes as $theme) {
            Theme::create($theme);
        }

        $this->command->info('✅ Themes seeded: ' . count($themes) . ' themes');
    }
}
