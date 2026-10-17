# Implementation Plan: MonetraPay CMS

## Overview

Implementation of a full-featured CMS for MonetraPay company profile website using Laravel 12, TailwindCSS 4, Spatie Permission, TinyMCE, and SortableJS. Tasks are organized to build foundational layers first (auth, models, services) then layer on features incrementally.

## Tasks

- [x] 1. Project setup and package installation
  - [x] 1.1 Install required packages (spatie/laravel-permission, intervention/image for thumbnails, spatie/laravel-sitemap)
    - Run `composer require spatie/laravel-permission intervention/image spatie/laravel-sitemap`
    - Publish Spatie Permission config and migration
    - _Requirements: 1.1, 1.5_
  - [x] 1.2 Create database migrations for all models
    - Create migrations for: blogs, categories, tags, blog_category, blog_tag, pages, menus, menu_items, media, media_folders, ads, settings
    - Follow schema defined in design document Data Models section
    - _Requirements: 2.1, 3.1, 4.2, 5.1, 9.1, 10.1_
  - [x] 1.3 Create Eloquent models with relationships
    - Create models: Blog, Category, Tag, Page, Menu, MenuItem, Media, MediaFolder, Ad, Setting
    - Define relationships (belongsTo, hasMany, belongsToMany, morphTo)
    - Add SoftDeletes trait to Blog and Page models
    - Update User model with Spatie HasRoles trait
    - _Requirements: 2.1, 3.1, 4.2, 5.1, 9.1_
  - [x] 1.4 Create database seeder for default roles and permissions
    - Seed permissions: view/create/edit/delete for each module (Blog, Page, Category, Menu, File, Ad, Setting, Analytics)
    - Seed default roles: Super Admin (all permissions), Admin (all except role/setting management), Editor (view+edit on Blog/Page/Category/File), Author (view+create on Blog)
    - Create default Super Admin user
    - _Requirements: 1.1, 1.5_

- [x] 2. Authentication and Role/Permission system
  - [x] 2.1 Set up Laravel authentication (login/logout)
    - Install Laravel Breeze or implement custom auth controllers
    - Create login page with TailwindCSS styling
    - Configure auth routes and redirects
    - _Requirements: 1.4_
  - [x] 2.2 Implement permission middleware and route protection
    - Register Spatie permission middleware in bootstrap/app.php
    - Create admin route group with auth + permission middleware
    - Implement 403 Forbidden page for unauthorized access
    - _Requirements: 1.4, 1.6_
  - [x] 2.3 Implement Role CRUD in admin panel
    - Create RoleController with index/create/store/edit/update/destroy actions
    - Create Blade views for role management (list, form)
    - Implement validation: unique name (max 50 chars), min 1 permission
    - Prevent deletion/modification of Super Admin role
    - _Requirements: 1.2, 1.3, 1.7, 1.8_
  - [x] 2.4 Implement User management in admin panel
    - Create UserController with CRUD actions
    - Create Blade views for user management
    - Implement role assignment to users
    - _Requirements: 1.1, 1.4_
  - [ ]* 2.5 Write property test for permission-based access control
    - **Property 3: Permission-based route access control**
    - **Validates: Requirements 1.4, 1.6, 1.7**

- [x] 3. Core services implementation
  - [x] 3.1 Implement SlugService
    - Create `app/Services/SlugService.php`
    - Implement `generate()`: lowercase, replace spaces/special chars with hyphens, remove non-alphanumeric except hyphens, trim hyphens, collapse consecutive hyphens
    - Implement `makeUnique()`: check database, append -2, -3, etc. if exists
    - _Requirements: 2.2, 2.3, 3.2, 3.3_
  - [ ]* 3.2 Write property test for slug generation
    - **Property 1: Slug generation produces valid format**
    - **Validates: Requirements 2.2, 3.2**
  - [ ]* 3.3 Write property test for slug uniqueness
    - **Property 2: Slug uniqueness resolution**
    - **Validates: Requirements 2.3, 3.3**
  - [x] 3.4 Implement AdInsertionService
    - Create `app/Services/AdInsertionService.php`
    - Implement `calculateInsertPositions()`: given paragraph count P and interval N, return positions [N, 2N, 3N, ...] where each <= P. Return empty array if P < N
    - Implement `insertAdsInContent()`: parse HTML, find `<p>` tags, insert ad HTML after calculated positions, preserve original content
    - _Requirements: 9.11, 9.12, 11.6_
  - [ ]* 3.5 Write property test for ad insertion
    - **Property 6: Auto-insert ad placement**
    - **Validates: Requirements 9.11, 9.12, 11.6**
  - [x] 3.6 Implement SeoService
    - Create `app/Services/SeoService.php`
    - Implement `getMetaTags()`: return meta title/description/keywords, fallback to global defaults
    - Implement `getOpenGraphTags()`: generate og:title, og:description, og:image, og:url, og:type
    - Implement `getTwitterCardTags()`: generate twitter:card, twitter:title, twitter:description, twitter:image
    - Implement `getCanonicalUrl()`: generate absolute URL for content
    - Implement `getStructuredData()`: generate JSON-LD for Article and Organization schemas
    - _Requirements: 7.1, 7.5, 7.6, 7.8, 7.9_
  - [ ]* 3.7 Write property test for SEO meta tag completeness
    - **Property 10: SEO meta tag completeness**
    - **Validates: Requirements 7.5, 7.6**
  - [x] 3.8 Implement SitemapService
    - Create `app/Services/SitemapService.php`
    - Implement `regenerate()`: query all published blogs, pages, categories; generate sitemap.xml in public directory
    - _Requirements: 7.2_
  - [ ]* 3.9 Write property test for sitemap completeness
    - **Property 9: Sitemap contains exactly published content**
    - **Validates: Requirements 7.2**
  - [x] 3.10 Implement MenuService
    - Create `app/Services/MenuService.php`
    - Implement `buildTree()`: convert flat menu_items to nested array structure
    - Implement `saveOrder()`: persist drag-and-drop order changes
    - Implement `validateStructure()`: check max 3 levels depth, max 50 items
    - _Requirements: 4.3, 4.8_
  - [ ]* 3.11 Write property test for menu structure validation
    - **Property 4: Menu structure depth validation**
    - **Validates: Requirements 4.3, 4.4**

- [x] 4. Checkpoint - Core services and auth
  - Ensure all tests pass, ask the user if questions arise.

- [x] 5. Blog management module
  - [x] 5.1 Implement BlogController (Admin)
    - Create `app/Http/Controllers/Admin/BlogController.php` with index/create/store/edit/update/destroy/restore actions
    - Implement search and filter (by status, category, date range)
    - Implement soft delete (move to trash) and restore
    - Use SlugService for auto-slug generation
    - _Requirements: 2.1, 2.2, 2.3, 2.4, 2.7, 2.8_
  - [x] 5.2 Create BlogRequest form validation
    - Create `app/Http/Requests/Admin/BlogRequest.php`
    - Validate: title (required, max:200), slug (max:220, unique, regex), content (required, max:100000), categories (required, array, min:1, max:5), tags (array, max:10), featured_image (image, max:2048, mimes:jpg,png,webp), meta_title (max:60), meta_description (max:160)
    - _Requirements: 2.1, 2.6, 2.9_
  - [x] 5.3 Create Blog admin Blade views
    - Create index view with table, search, filters (status/category/date)
    - Create create/edit form with TinyMCE editor integration
    - Create trash view with restore/permanent delete actions
    - Style with TailwindCSS
    - _Requirements: 2.1, 2.7, 2.8_
  - [ ]* 5.4 Write property test for blog filter correctness
    - **Property 11: Blog filter correctness**
    - **Validates: Requirements 2.7**
  - [x] 5.5 Implement CategoryController (Admin)
    - Create CRUD controller for categories
    - Create Blade views for category management
    - Use SlugService for category slugs
    - _Requirements: 2.5_
  - [x] 5.6 Create BlogObserver for sitemap regeneration
    - Create `app/Observers/BlogObserver.php`
    - On created/updated/deleted events, trigger SitemapService::regenerate()
    - _Requirements: 7.2_

- [x] 6. Page management module
  - [x] 6.1 Implement PageController (Admin)
    - Create `app/Http/Controllers/Admin/PageController.php` with CRUD actions
    - Implement parent-child hierarchy (max 3 levels)
    - Use SlugService for auto-slug generation
    - Implement soft delete and restore
    - _Requirements: 3.1, 3.2, 3.3, 3.4, 3.7, 3.8_
  - [x] 6.2 Create PageRequest form validation
    - Validate: title (required, max:200), slug (max:200, unique), parent_id (exists, depth check), template (in:available), content (max:100000)
    - Custom validation rule for max 3 level depth
    - _Requirements: 3.1, 3.3, 3.5, 3.7_
  - [x] 6.3 Create Page admin Blade views
    - Create index view showing page hierarchy
    - Create create/edit form with parent page selector, template selector, TinyMCE editor
    - Style with TailwindCSS
    - _Requirements: 3.1, 3.6_
  - [x] 6.4 Create PageObserver for sitemap regeneration
    - On created/updated/deleted events, trigger SitemapService::regenerate()
    - _Requirements: 7.2_

- [x] 7. Menu builder module
  - [x] 7.1 Implement MenuController (Admin)
    - Create `app/Http/Controllers/Admin/MenuController.php`
    - Implement CRUD for menus (name, location)
    - Implement menu item management with drag-and-drop ordering
    - Use MenuService for tree building and validation
    - _Requirements: 4.1, 4.2, 4.3, 4.5, 4.7, 4.8_
  - [x] 7.2 Create Menu builder Blade views with SortableJS
    - Create menu list view (manage multiple menus by location)
    - Create menu editor with drag-and-drop interface using SortableJS
    - Implement add item modal (custom link, page, category, external URL)
    - Implement nesting via drag indentation (max 3 levels)
    - Validate URL format for external links
    - Style with TailwindCSS
    - _Requirements: 4.1, 4.2, 4.3, 4.4, 4.6, 4.9_
  - [x] 7.3 Create MenuItemRequest validation
    - Validate: label (required, max:100), url (required_if type is custom/external, url format), type (required, in:custom,page,category,external)
    - _Requirements: 4.2, 4.9_

- [x] 8. File manager module
  - [x] 8.1 Implement FileManagerController (Admin)
    - Create `app/Http/Controllers/Admin/FileManagerController.php`
    - Implement file upload with validation (type + size)
    - Implement folder CRUD (max 5 levels depth)
    - Implement file listing with grid view and thumbnails
    - Implement file search (min 2 chars)
    - Implement file deletion with usage check
    - Generate thumbnails (150x150) for images using Intervention Image
    - _Requirements: 5.1, 5.2, 5.3, 5.4, 5.5, 5.7, 5.8_
  - [x] 8.2 Create File Manager Blade views
    - Create grid view with thumbnails, file info (name, size, date)
    - Create folder navigation breadcrumb
    - Create upload modal with drag-and-drop zone
    - Create folder creation modal
    - Create search input with live filtering
    - Create modal version for TinyMCE integration (image picker)
    - Style with TailwindCSS
    - _Requirements: 5.1, 5.4, 5.5, 5.6, 5.7_
  - [ ]* 8.3 Write property test for file upload validation
    - **Property 5: File upload validation**
    - **Validates: Requirements 5.2, 5.3**

- [x] 9. Rich text editor integration
  - [x] 9.1 Integrate TinyMCE with File Manager
    - Add TinyMCE via CDN or npm package
    - Configure toolbar: bold, italic, underline, h1-h6, lists, blockquote, link, code, image, video embed, fullscreen
    - Implement custom image picker button that opens File Manager modal
    - Implement video embed dialog with YouTube/Vimeo URL validation
    - Configure output to produce semantic HTML (no inline styles, no empty tags)
    - Set max content length to 100,000 characters
    - _Requirements: 6.1, 6.2, 6.3, 6.4, 6.5, 6.6, 6.7, 6.8_
  - [ ]* 9.2 Write property test for video embed URL transformation
    - **Property 8: Video embed URL transformation**
    - **Validates: Requirements 6.4, 6.5**
  - [ ]* 9.3 Write property test for HTML output semantic validity
    - **Property 7: Text editor HTML output semantic validity**
    - **Validates: Requirements 6.6**

- [x] 10. Checkpoint - Admin modules complete
  - Ensure all tests pass, ask the user if questions arise.

- [x] 11. Ad management module
  - [x] 11.1 Implement AdController (Admin)
    - Create `app/Http/Controllers/Admin/AdController.php` with CRUD actions
    - Implement ad slot creation with type selection (adsense/adstera/manual)
    - Implement position selection (header/sidebar/in_content/auto_insert/footer)
    - Implement scheduling with date validation (start not in past, end after start)
    - Implement activate/deactivate toggle
    - _Requirements: 9.1, 9.2, 9.3, 9.4, 9.5, 9.8, 9.9, 9.10_
  - [x] 11.2 Create AdRequest form validation
    - Validate: name (required, max:100), type (required, in:adsense,adstera,manual), position (required), code (max:5000), image (max:2048, mimes:jpg,png,gif), paragraph_number (1-20 for in_content), interval (2-10 for auto_insert), schedule dates
    - _Requirements: 9.2, 9.8, 9.9_
  - [x] 11.3 Create Ad admin Blade views
    - Create index view with ad list, status toggle, position info
    - Create create/edit form with dynamic fields based on type/position
    - Style with TailwindCSS
    - _Requirements: 9.1, 9.2, 9.3_
  - [x] 11.4 Create scheduled command for ad deactivation
    - Create `app/Console/Commands/DeactivateExpiredAds.php`
    - Query ads where end_date < today and is_active = true, set is_active = false
    - Register in scheduler (daily)
    - _Requirements: 9.6_
  - [ ]* 11.5 Write property test for ad schedule deactivation
    - **Property 12: Ad schedule auto-deactivation**
    - **Validates: Requirements 9.6**

- [x] 12. Settings management module
  - [x] 12.1 Implement SettingController (Admin)
    - Create `app/Http/Controllers/Admin/SettingController.php`
    - Implement grouped settings: general, social media, SEO, footer
    - Implement maintenance mode toggle
    - Validate all fields per requirements (email format, URL format, file sizes, character limits)
    - Allow optional fields to be empty
    - _Requirements: 10.1, 10.2, 10.3, 10.4, 10.5, 10.6, 10.7, 10.8_
  - [x] 12.2 Create Settings admin Blade views
    - Create tabbed interface for setting groups (General, Social, SEO, Footer)
    - Create file upload for logo and favicon
    - Create maintenance mode toggle with confirmation
    - Style with TailwindCSS
    - _Requirements: 10.1, 10.2, 10.3, 10.4_
  - [x] 12.3 Implement Google Analytics integration
    - Create `app/Services/AnalyticsService.php`
    - Implement GA Data API v1 client with OAuth or service account
    - Implement getSummary(), getDailyTrend(), getTopPages() with 60-minute cache
    - Handle API failures gracefully (show error, serve cached data)
    - _Requirements: 8.1, 8.3, 8.4, 8.5, 8.6, 8.7_
  - [x] 12.4 Create Analytics Dashboard widget in admin
    - Create dashboard widget showing: total users, pageviews, bounce rate, avg session duration
    - Create daily visitor trend chart (30 days)
    - Create top 10 pages list
    - Show configuration prompt if GA ID not set
    - _Requirements: 8.3, 8.4, 8.5, 8.6, 8.7_

- [x] 13. SEO management implementation
  - [x] 13.1 Implement SEO components and robots.txt
    - Create `resources/views/components/seo-meta.blade.php` component
    - Render meta tags, OG tags, Twitter Card tags, canonical URL, JSON-LD
    - Implement configurable robots.txt route
    - Implement sitemap.xml route
    - _Requirements: 7.1, 7.3, 7.4, 7.5, 7.6, 7.7, 7.8_
  - [x] 13.2 Implement SEO character count indicators
    - Add JavaScript character counter on meta title (warn > 60) and meta description (warn > 160) fields in blog/page forms
    - _Requirements: 7.10_

- [x] 14. Checkpoint - All admin features complete
  - Ensure all tests pass, ask the user if questions arise.

- [ ] 15. Frontend public pages
  - [x] 15.1 Create frontend layout and shared components
    - Create `resources/views/frontend/layouts/app.blade.php` with TailwindCSS
    - Create navbar component rendering menu from Menu_Builder config (dropdown up to 3 levels)
    - Create footer component with contact info, social links, footer menu from Settings
    - Create breadcrumb component (Home > Parent > Current)
    - Create skip-to-content link (visually hidden, visible on focus)
    - Include GA tracking code if configured
    - Implement responsive breakpoints (mobile < 768px, tablet 768-1023px, desktop 1024px+)
    - _Requirements: 11.2, 11.3, 11.10, 12.2, 12.3, 12.4_
  - [x] 15.2 Implement homepage
    - Create `resources/views/frontend/home.blade.php`
    - Hero section with heading, MonetraPay description (pulsa, PPOB, top-up game, e-money), CTA button
    - Use semantic HTML (header, nav, main, article, aside, footer)
    - Ensure single H1 per page
    - _Requirements: 11.1, 11.2, 12.3_
  - [x] 15.3 Implement blog listing page
    - Create `resources/views/frontend/blog/index.blade.php`
    - Pagination (10 per page), category filter, sidebar
    - Lazy loading for images with width/height attributes
    - _Requirements: 11.4, 12.1_
  - [x] 15.4 Implement blog detail page
    - Create `resources/views/frontend/blog/show.blade.php`
    - Full content, author name, publication date
    - Related articles (max 3 from same category)
    - Auto-insert ads using AdInsertionService
    - SEO meta tags, OG tags, Twitter Card, JSON-LD (Article schema)
    - Breadcrumb navigation
    - _Requirements: 11.5, 11.6, 7.5, 7.8, 12.2_
  - [x] 15.5 Implement category and page views
    - Create category listing page (with article count per category)
    - Create category detail page (articles in category with pagination)
    - Create static page view (render page content based on slug)
    - Breadcrumb navigation on all pages
    - _Requirements: 11.7, 11.8, 12.2_
  - [x] 15.6 Implement search functionality
    - Create `resources/views/frontend/search.blade.php`
    - Search across blog posts and pages (min 3 chars)
    - Display results or "tidak ada hasil" message
    - _Requirements: 11.9_
  - [x] 15.7 Implement maintenance mode and error pages
    - Create `app/Http/Middleware/CheckMaintenanceMode.php`
    - Create Coming Soon page (shown to unauthenticated visitors when maintenance active)
    - Create 404 page with navigation back to homepage
    - Create 403 page
    - Register middleware in web route group
    - _Requirements: 10.7, 11.11, 11.12_
  - [x] 15.8 Implement ad rendering on frontend
    - Create `resources/views/components/ad-slot.blade.php`
    - Render ads in header, sidebar, footer positions
    - Render in-content ads at specified paragraph positions
    - Hide inactive ads without leaving empty space
    - _Requirements: 9.4, 9.5, 9.7_
  - [x] 15.9 Implement frontend performance and accessibility
    - Add `loading="lazy"` with explicit width/height to all below-fold images
    - Use CSS transitions max 300ms on navigation/UI interactions
    - Implement `prefers-reduced-motion: reduce` media query to disable animations
    - Ensure semantic HTML landmarks on every page (header, nav, main, footer)
    - _Requirements: 12.1, 12.3, 12.4, 12.5, 12.6_

- [x] 16. Scheduled commands and cleanup
  - [x] 16.1 Create trash cleanup command
    - Create `app/Console/Commands/CleanTrash.php`
    - Force-delete blogs and pages where deleted_at > 30 days ago
    - Register in scheduler (daily)
    - _Requirements: 2.8, 3.8_
  - [x] 16.2 Register all scheduled commands
    - Register DeactivateExpiredAds (daily)
    - Register CleanTrash (daily)
    - _Requirements: 2.8, 3.8, 9.6_

- [x] 17. Admin panel layout and navigation
  - [x] 17.1 Create admin layout with permission-based sidebar
    - Create `resources/views/admin/layouts/app.blade.php`
    - Sidebar navigation showing only modules user has permission to access
    - Use Spatie `@can` Blade directives for menu visibility
    - Responsive admin layout with TailwindCSS
    - Dashboard page with Analytics widget
    - _Requirements: 1.4, 8.3_

- [x] 18. Final checkpoint - Full integration
  - Ensure all tests pass, ask the user if questions arise.

## Task Dependency Graph

```json
{
  "waves": [
    {
      "name": "Wave 1: Foundation",
      "tasks": ["1"],
      "description": "Project setup, packages, migrations, models, seeders"
    },
    {
      "name": "Wave 2: Auth & Core Services",
      "tasks": ["2", "3"],
      "description": "Authentication, role/permission system, and core service classes"
    },
    {
      "name": "Wave 3: Admin Modules",
      "tasks": ["5", "6", "7", "8", "9"],
      "description": "Blog, Page, Menu, File Manager, and Rich Text Editor modules"
    },
    {
      "name": "Wave 4: Extended Admin Features",
      "tasks": ["11", "12", "13"],
      "description": "Ad management, Settings, SEO, and Analytics"
    },
    {
      "name": "Wave 5: Frontend & Finalization",
      "tasks": ["15", "16", "17"],
      "description": "Public frontend pages, scheduled commands, admin layout"
    }
  ]
}
```

## Notes

- Tasks marked with `*` are optional property-based tests and can be skipped for faster MVP
- Each task references specific requirements for traceability
- Checkpoints ensure incremental validation
- Property tests validate universal correctness properties from the design document
- The project uses Pest 3 for testing (already configured)
- TailwindCSS 4 is already set up with Vite — no additional CSS framework needed
- All admin routes should be prefixed with `/admin` and protected by auth + permission middleware
- Frontend routes use clean URLs based on slugs
