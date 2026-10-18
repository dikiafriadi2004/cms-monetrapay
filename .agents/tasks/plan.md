# Implementation Plan — CMS MonetraPay UI/Builder Rebuild

This plan covers four separable features executed in dependency order.
All work is inside the worktree `e:\MUDK Project\project\cms-monetrapay`.

---

- [ ] 1. **FEAT-006 — Fix ThemeService CSS variable output in frontend layout**

  The `ThemeService::applyThemeStyles()` method already generates `:root { --theme-primary: …; }` CSS
  but it is never called from `resources/views/frontend/layouts/app.blade.php`.
  The builder view (`resources/views/frontend/page/builder.blade.php`) calls it via `@push('head')`,
  but `app.blade.php` does not `@stack('head')` — so even that is silently dropped.

  **Decision:** Inject theme CSS variables directly into `app.blade.php` inside `<head>`,
  just before `@vite(…)`. Use a `@php` block calling `app(\App\Services\ThemeService::class)->applyThemeStyles()`.
  This is the simplest, zero-JS approach that works for every page type.
  No new layout files are needed because the existing monolithic layout is already shared.

  Files to modify:
  - `resources/views/frontend/layouts/app.blade.php` — add `@stack('head')` before `</head>` and add theme-style inline block

  Files to create:
  - `resources/views/themes/` — *not needed* — the task description mentions theme layout files
    but the codebase has a single shared layout. Creating per-theme full layout files would require
    a routing change and ThemeService wiring that exceeds what is broken. The correct minimal fix is
    CSS variable injection. See FEAT-007 for the full theme layout switcher if desired.

  Verify: Load any frontend page in browser, view source, confirm `<style>:root { --theme-primary: #3D9E7A; … }</style>` appears in `<head>`.
  Also run `php artisan view:clear` and confirm no Blade errors.

---

- [ ] 2. **FEAT-007 — Create 5 distinct professional theme layout files + ThemeService layout switcher**

  ThemeSeeder defines 10 themes but none have distinct visual layouts — they all use the same
  `resources/views/frontend/layouts/app.blade.php`. The task requires 5 distinct professional layouts.

  **Decision:** Create 5 theme layout variants in `resources/views/themes/` that each extend a
  shared base (`resources/views/frontend/layouts/app.blade.php`) or copy it with distinct structural
  variations (header style, color scheme usage). Add a `getActiveLayout()` method to `ThemeService`
  that returns the layout name based on `$theme->config['header_style']` (or slug).
  Update `PageFrontController` and `FrontendComposer` to pass the layout name to all frontend views.
  Use `@extends($layout ?? 'frontend.layouts.app')` in frontend page views.

  Theme layout files to create (all in `resources/views/themes/`):
  - `modern-corporate.blade.php` — sticky white header, boxed 1280px max, professional
  - `creative-agency.blade.php` — transparent header over hero, full-width gradient, bold
  - `minimalist-blog.blade.php` — serif fonts, narrow centered column, minimal header
  - `tech-startup.blade.php` — dark gradient navbar, glassmorphism cards, modern
  - `dark-mode-elegant.blade.php` — dark bg default, purple/blue accent, elegant

  Files to modify:
  - `app/Services/ThemeService.php` — add `getActiveLayout(): string` method
  - `app/View/Composers/FrontendComposer.php` — share `$activeLayout` with frontend views
  - `resources/views/frontend/page/show.blade.php` — use `@extends($activeLayout ?? 'frontend.layouts.app')`
  - `resources/views/frontend/page/builder.blade.php` — use `@extends($activeLayout ?? 'frontend.layouts.app')`
  - `resources/views/frontend/page/kontak.blade.php` — same
  - `resources/views/frontend/page/layanan.blade.php` — same
  - `resources/views/frontend/page/tentang-kami.blade.php` — same
  - `resources/views/frontend/home.blade.php` — same
  - `resources/views/frontend/blog/index.blade.php` — same
  - `resources/views/frontend/blog/show.blade.php` — same

  Verify: `php artisan view:clear && php artisan route:clear` — no errors.
  Activate "Tech Startup" theme in admin, visit homepage, confirm dark gradient navbar appears.
  Activate "Modern Corporate", confirm standard white navbar.

---

- [ ] 3. **FEAT-008 — Rebuild 15 builder components with professional Tailwind design**

  Current components use generic gray/gray-50 backgrounds and basic styling.
  All 15 need a professional upgrade using Tailwind CSS classes consistent with the
  site's existing color tokens (`text-primary`, `bg-primary`, `text-secondary`, etc.) and
  design language from `app.blade.php` (rounded-2xl, shadow-xl, gradient text, etc.).

  Key design decisions:
  - Hero: full-viewport optional, gradient overlay, animated floating shapes, dual-CTA pattern
  - Text: proper `prose-enhanced` wrapper (already defined in app.css), max-width control
  - Features: icon containers with gradient bg, hover-lift cards, section heading with badge
  - CTA: gradient background using theme CSS vars, particle decoration, bold typography
  - Testimonials: card grid with star rating, avatar fallback initials, subtle shadow
  - Pricing: highlighted "popular" card with ring, feature list with check icons
  - Team: photo aspect-ratio card, role badge, social icon row
  - Gallery: masonry-style hover zoom, lightbox-ready markup
  - FAQ: Alpine.js accordion already present, enhance with border-primary on open state
  - Contact Form: already well-built; minor spacing/icon polish only
  - Video: responsive 16:9 embed container, loading="lazy" iframe
  - Map: responsive iframe, address overlay card
  - Social: icon grid with hover color fill per platform
  - Newsletter: inline subscribe form with primary button
  - HTML: passthrough with warning note in admin

  Files to modify (all 15):
  - `resources/views/components/builder/hero.blade.php`
  - `resources/views/components/builder/text.blade.php`
  - `resources/views/components/builder/features.blade.php`
  - `resources/views/components/builder/cta.blade.php`
  - `resources/views/components/builder/testimonials.blade.php`
  - `resources/views/components/builder/pricing.blade.php`
  - `resources/views/components/builder/team.blade.php`
  - `resources/views/components/builder/gallery.blade.php`
  - `resources/views/components/builder/faq.blade.php`
  - `resources/views/components/builder/contact-form.blade.php`
  - `resources/views/components/builder/video.blade.php`
  - `resources/views/components/builder/map.blade.php`
  - `resources/views/components/builder/social.blade.php`
  - `resources/views/components/builder/newsletter.blade.php`
  - `resources/views/components/builder/html.blade.php`

  Verify: `php artisan view:clear`. Create a test page with `use_page_builder=true` in the DB and
  add one of each component type. Visit the page in browser and confirm components render without
  errors and look professional. Run `php artisan test --filter=Page` to confirm no regressions.

---

- [ ] 4. **FEAT-009 — Rebuild admin page builder UI into WordPress-like two-panel drag-drop editor**

  Current editor (`admin/page-builder/edit.blade.php`) requires raw JSON input and has no
  drag-drop, no inline editing fields, and no auto-save. The save route exists at
  `POST admin/pages/{page}/builder/save` and accepts `components[]` as an array.

  **Architecture decision:** Pure server-rendered Blade + Alpine.js + SortableJS (loaded from CDN
  to avoid adding npm deps). No Vue/React — matches existing Alpine.js usage in the project.
  Auto-save via `fetch()` POST to the existing `admin.page-builder.save` route every 30s or on
  significant edit (matching the existing autosave pattern in `admin.js`).

  Two-panel layout:
  - **Left panel (70%):** Canvas showing current components as draggable cards with SortableJS.
    Each card shows component type icon, title, visibility toggle, edit/delete buttons.
    Click "Edit" on a card to open an inline settings accordion below that card.
  - **Right panel (30%):** Component type picker (15 buttons with emoji/icon + name + desc).
    Click a type to append a new component to the canvas with default content.
    Below the picker: Save button, page settings summary (slug, status).

  Per-component inline edit form (Alpine.js `x-show` on each canvas card):
  - hero: title, subtitle, button_text, button_link, background_image, height (select), text_align (select)
  - text: heading, text (textarea), text_align (select), max_width (select)
  - features: heading, columns (select), then repeating rows: icon, title, description
  - cta: heading, text, button_text, button_link, style (select: gradient/solid/outline)
  - testimonials: heading, columns (select), repeating: name, position, text, rating, photo
  - pricing: heading, repeating: name, description, price, currency, period, featured (checkbox), button_text, button_link, features (textarea one-per-line)
  - team: heading, columns, repeating: name, position, bio, photo
  - gallery: heading, columns, layout (select), repeating: url, alt, caption
  - faq: heading, repeating: question, answer
  - contact-form: title, subtitle, button_text, show_city (checkbox)
  - video: heading, url (YouTube/Vimeo), caption
  - map: heading, embed_url, address, zoom
  - social: heading, repeating: platform, url
  - newsletter: heading, subtitle, placeholder, button_text
  - html: content (textarea, raw HTML warning)

  Files to create/modify:
  - `resources/views/admin/page-builder/edit.blade.php` — full rebuild (see above)

  Verify: Visit `admin/pages/{any_builder_page}/builder`. Confirm two-panel layout renders.
  Add a hero component, fill in fields, drag to reorder, click Save. Reload page — components
  persist. Run `php artisan test --filter=PageBuilder` if tests exist.

---

## Migration / Seeder Changes

No new migrations required. The `page_components` and `themes` tables already exist.
No seeder changes required for this task.

## Dependency Order

1 → 2 (theme CSS injection before layout switcher)
3 is independent of 1 and 2 (component files don't depend on layout)
4 is independent of 1, 2, 3 (admin editor doesn't depend on frontend components)

Items 3 and 4 can be worked in parallel conceptually, but are executed sequentially.
