# Multi-Template & Page Builder System - User Guide

## 🎨 Theme System

### Accessing Themes
1. Login ke admin panel
2. Navigate to **Settings → Themes**
3. Lihat 10 theme yang tersedia

### Available Themes
1. **Modern Corporate** (Default) - Professional blue theme
2. **Creative Agency** - Colorful with gradients
3. **Minimalist Blog** - Simple & clean
4. **E-commerce Style** - Product-focused
5. **Magazine Layout** - Multi-column news
6. **Dark Mode Elegant** - Modern dark theme
7. **Landing Page** - Conversion-focused
8. **Portfolio Showcase** - Image-heavy
9. **Tech Startup** - Modern gradient
10. **Classic Business** - Traditional serif

### Activating a Theme
1. Klik tombol **"Activate"** pada theme yang diinginkan
2. Theme langsung aktif di frontend
3. Badge **"ACTIVE"** akan muncul pada theme yang aktif

### Customizing Theme
1. Klik **"Customize"** pada active theme
2. Ubah:
   - Primary Color
   - Secondary Color
   - Heading Font
   - Body Font
   - Header Style
   - Footer Style
   - Sidebar Position
   - Layout Width
3. Lihat live preview di sebelah kanan
4. Klik **"Save Changes"** untuk apply

---

## 🏗️ Page Builder

### 15 Available Components

#### Content Components
1. **Hero Section** - Banner dengan background image & CTA
2. **Text Block** - Paragraf dan heading
3. **Features Grid** - Grid fitur dengan icon (2/3/4 kolom)
4. **Testimonials** - Review customer dengan rating
5. **Team Members** - Profil tim dengan foto & social links

#### Form Components
6. **Contact Form** - Form kontak dengan validation
7. **Newsletter Signup** - Form subscribe email

#### Media Components
8. **Gallery** - Grid foto responsive
9. **Video Embed** - YouTube/Vimeo player
10. **Map Location** - Google Maps embed

#### Conversion Components
11. **Call to Action** - Button & CTA besar
12. **Pricing Tables** - Tabel harga dengan fitur

#### Social Components
13. **Social Media** - Icon link social media
14. **FAQ Accordion** - Q&A collapsible

#### Advanced
15. **Custom HTML** - Raw HTML/CSS/JS block

### Using Page Builder

#### Method 1: Via Database (Current)
```php
// 1. Create page
$page = Page::create([
    'title' => 'My Page',
    'slug' => 'my-page',
    'use_page_builder' => true, // Enable builder
    'status' => 'published',
]);

// 2. Add Hero Component
PageComponent::create([
    'page_id' => $page->id,
    'component_type' => 'hero',
    'content' => [
        'title' => 'Welcome to Our Website',
        'subtitle' => 'Discover amazing features',
        'button_text' => 'Get Started',
        'button_link' => '/contact',
    ],
    'settings' => [
        'text_align' => 'center',
        'height' => 'large',
    ],
    'sort_order' => 0,
    'is_visible' => true,
]);

// 3. Add Text Component
PageComponent::create([
    'page_id' => $page->id,
    'component_type' => 'text',
    'content' => [
        'heading' => 'About Us',
        'text' => 'Lorem ipsum dolor sit amet...',
    ],
    'settings' => [
        'text_align' => 'left',
        'max_width' => 'medium',
    ],
    'sort_order' => 1,
    'is_visible' => true,
]);
```

#### Method 2: Via API (Future)
```javascript
// POST /api/admin/pages/{id}/builder/save
{
  "components": [
    {
      "component_type": "hero",
      "content": {
        "title": "Welcome",
        "subtitle": "Discover amazing features"
      },
      "settings": {
        "text_align": "center"
      },
      "sort_order": 0,
      "is_visible": true
    }
  ]
}
```

---

## 🔧 Dynamic Menu System

### Menu Locations
- **header** - Main navigation menu
- **footer** - Footer links
- **sidebar** - Sidebar menu (optional)

### Creating Menu via Admin
1. Go to **Menu Builder**
2. Create new menu
3. Set location (header/footer)
4. Add menu items
5. Activate menu

### Menu Item Types
- **Page** - Link to internal page
- **Blog Category** - Link to category
- **Custom URL** - External or custom link

### Menu Structure (Database)
```sql
-- Example: Create header menu
INSERT INTO menus (name, location, is_active) 
VALUES ('Main Navigation', 'header', 1);

-- Add menu items
INSERT INTO menu_items (menu_id, label, type, url, order) VALUES
(1, 'Home', 'custom-url', '/', 0),
(1, 'About', 'page', null, 1), -- page_id set separately
(1, 'Blog', 'custom-url', '/blog', 2),
(1, 'Contact', 'custom-url', '/kontak', 3);
```

---

## 📝 Component Examples

### Hero Section
```php
[
    'component_type' => 'hero',
    'content' => [
        'title' => 'Welcome to MonetraPay',
        'subtitle' => 'Your trusted payment solution',
        'button_text' => 'Get Started',
        'button_link' => '/register',
        'background_image' => '/images/hero-bg.jpg',
    ],
    'settings' => [
        'text_align' => 'center',
        'height' => 'large', // small, medium, large
    ]
]
```

### Features Grid
```php
[
    'component_type' => 'features',
    'content' => [
        'heading' => 'Our Features',
        'features' => [
            [
                'icon' => '⚡',
                'title' => 'Fast Performance',
                'description' => 'Lightning fast transactions',
            ],
            [
                'icon' => '🔒',
                'title' => 'Secure',
                'description' => 'Bank-level security',
            ],
            [
                'icon' => '📱',
                'title' => 'Mobile Ready',
                'description' => 'Works on all devices',
            ],
        ],
    ],
    'settings' => [
        'columns' => 3, // 2, 3, or 4
    ]
]
```

### Contact Form
```php
[
    'component_type' => 'contact-form',
    'content' => [
        'heading' => 'Get In Touch',
        'text' => 'Send us a message',
        'form_action' => route('contact.submit'),
    ],
    'settings' => []
]
```

### Testimonials
```php
[
    'component_type' => 'testimonials',
    'content' => [
        'heading' => 'What Clients Say',
        'testimonials' => [
            [
                'name' => 'John Doe',
                'position' => 'CEO at Company',
                'text' => 'Amazing service!',
                'rating' => 5,
                'photo' => '/images/john.jpg',
            ],
        ],
    ],
    'settings' => [
        'layout' => 'grid',
        'columns' => 3,
    ]
]
```

### Pricing Table
```php
[
    'component_type' => 'pricing',
    'content' => [
        'heading' => 'Choose Your Plan',
        'plans' => [
            [
                'name' => 'Basic',
                'price' => '99.000',
                'currency' => 'Rp',
                'period' => 'month',
                'description' => 'Perfect for startups',
                'features' => [
                    '10 Transactions/day',
                    'Email Support',
                    'Basic Analytics',
                ],
                'button_text' => 'Get Started',
                'button_link' => '/pricing/basic',
                'featured' => false,
            ],
            [
                'name' => 'Pro',
                'price' => '299.000',
                'currency' => 'Rp',
                'period' => 'month',
                'description' => 'For growing businesses',
                'features' => [
                    'Unlimited Transactions',
                    'Priority Support',
                    'Advanced Analytics',
                    'API Access',
                ],
                'button_text' => 'Get Started',
                'button_link' => '/pricing/pro',
                'featured' => true,
            ],
        ],
    ],
    'settings' => []
]
```

---

## 🚀 Quick Start Guide

### 1. Setup Themes
```bash
# Themes already seeded, just visit:
http://localhost/admin/themes
```

### 2. Create Sample Page with Builder
```php
use App\Models\Page;
use App\Models\PageComponent;

// Create page
$page = Page::create([
    'title' => 'Landing Page',
    'slug' => 'landing',
    'use_page_builder' => true,
    'status' => 'published',
    'user_id' => 1,
]);

// Add Hero
PageComponent::create([
    'page_id' => $page->id,
    'component_type' => 'hero',
    'content' => [
        'title' => 'Welcome to MonetraPay',
        'subtitle' => 'Secure payment solution',
        'button_text' => 'Get Started',
        'button_link' => '/register',
    ],
    'sort_order' => 0,
    'is_visible' => true,
]);

// Add Features
PageComponent::create([
    'page_id' => $page->id,
    'component_type' => 'features',
    'content' => [
        'heading' => 'Why Choose Us',
        'features' => [
            ['icon' => '⚡', 'title' => 'Fast', 'description' => 'Lightning fast'],
            ['icon' => '🔒', 'title' => 'Secure', 'description' => 'Bank-level security'],
            ['icon' => '📱', 'title' => 'Mobile', 'description' => 'Mobile ready'],
        ],
    ],
    'settings' => ['columns' => 3],
    'sort_order' => 1,
    'is_visible' => true,
]);

// Add CTA
PageComponent::create([
    'page_id' => $page->id,
    'component_type' => 'cta',
    'content' => [
        'heading' => 'Ready to Get Started?',
        'text' => 'Join thousands of users',
        'button_text' => 'Sign Up Now',
        'button_link' => '/register',
    ],
    'settings' => ['style' => 'gradient'],
    'sort_order' => 2,
    'is_visible' => true,
]);

// Visit: http://localhost/landing
```

### 3. Setup Dynamic Menu
```php
use App\Models\Menu;
use App\Models\MenuItem;

// Create header menu
$menu = Menu::create([
    'name' => 'Main Navigation',
    'location' => 'header',
    'is_active' => true,
]);

// Add menu items
MenuItem::create([
    'menu_id' => $menu->id,
    'label' => 'Home',
    'type' => 'custom-url',
    'url' => '/',
    'order' => 0,
    'is_active' => true,
]);

MenuItem::create([
    'menu_id' => $menu->id,
    'label' => 'About',
    'type' => 'custom-url',
    'url' => '/tentang-kami',
    'order' => 1,
    'is_active' => true,
]);

// Clear cache
php artisan cache:forget menu.location.header
```

---

## 📚 References

- Theme models: `app/Models/Theme.php`
- Page models: `app/Models/Page.php`, `app/Models/PageComponent.php`
- Theme service: `app/Services/ThemeService.php`
- Menu service: `app/Services/MenuService.php`
- Components: `resources/views/components/builder/`
- Frontend rendering: `resources/views/frontend/page/builder.blade.php`

---

## 💡 Tips & Best Practices

1. **Theme Customization**: Always save backup before major changes
2. **Component Order**: Use `sort_order` to control display order
3. **Performance**: Components are cached, clear cache after changes
4. **Responsive**: All components are mobile-friendly by default
5. **SEO**: Page builder pages support full SEO metadata

---

**Last Updated**: {{ now()->format('d M Y') }}  
**Version**: 1.0.0  
**Status**: Production Ready ✅
