<?php

use App\Http\Controllers\Admin\AdController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ContactSubmissionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FileManagerController;
use App\Http\Controllers\Admin\MaintenanceController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\NewsletterCampaignController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SeoToolController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TwoFactorController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Frontend\BlogFrontController;
use App\Http\Controllers\Frontend\CommentController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\FeedController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\NewsletterController;
use App\Http\Controllers\Frontend\PageFrontController;
use App\Http\Controllers\Frontend\SearchController;
use App\Http\Controllers\Frontend\SeoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Frontend Routes (public)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/blog', [BlogFrontController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogFrontController::class, 'show'])->name('blog.show');
Route::get('/categories', [BlogFrontController::class, 'categories'])->name('categories.index');
Route::get('/category/{slug}', [BlogFrontController::class, 'category'])->name('category.show');

/*
|--------------------------------------------------------------------------
| Frontend Search Route
|--------------------------------------------------------------------------
*/
Route::get('/search', [SearchController::class, 'index'])->name('search');

/*
|--------------------------------------------------------------------------
| Contact Form Submission (rate-limited)
|--------------------------------------------------------------------------
*/
Route::post('/kontak', [ContactController::class, 'submit'])
    ->middleware('throttle:10,1')
    ->name('contact.submit');

/*
|--------------------------------------------------------------------------
| Comments
|--------------------------------------------------------------------------
*/
Route::post('/blog/{blog:slug}/comments', [CommentController::class, 'store'])
    ->middleware('throttle:20,1')
    ->name('comments.store');

/*
|--------------------------------------------------------------------------
| Newsletter
|--------------------------------------------------------------------------
*/
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])
    ->middleware('throttle:5,1')
    ->name('newsletter.subscribe');
Route::get('/newsletter/confirm/{token}', [NewsletterController::class, 'confirm'])
    ->name('newsletter.confirm');
Route::get('/newsletter/unsubscribe/{token}', [NewsletterController::class, 'unsubscribe'])
    ->name('newsletter.unsubscribe');

/*
|--------------------------------------------------------------------------
| SEO Routes (public)
|--------------------------------------------------------------------------
*/
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('robots');
Route::get('/feed.xml', [FeedController::class, 'rss'])->name('feed.rss');
Route::get('/rss', fn () => redirect('/feed.xml', 301));

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Password Reset
|--------------------------------------------------------------------------
*/
Route::get('/forgot-password', [PasswordResetController::class, 'requestForm'])->name('password.request');
Route::post('/forgot-password', [PasswordResetController::class, 'sendLink'])->name('password.email')->middleware('throttle:5,1');
Route::get('/reset-password/{token}', [PasswordResetController::class, 'resetForm'])->name('password.reset');
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->name('password.update');

/*
|--------------------------------------------------------------------------
| 2FA Challenge (guests who have pending 2FA session)
|--------------------------------------------------------------------------
*/
Route::get('/two-factor/challenge', [TwoFactorController::class, 'challenge'])->name('two-factor.challenge');
Route::post('/two-factor/challenge', [TwoFactorController::class, 'verify'])->name('two-factor.verify');

/*
|--------------------------------------------------------------------------
| Admin Routes (protected by auth middleware)
|--------------------------------------------------------------------------
| All admin routes require authentication. Permission-based access control
| is applied per-route or per-group using the 'check.permission' middleware.
| Usage: ->middleware('check.permission:blog.view')
|
| Available permission format: {module}.{operation}
| Modules: blog, page, category, menu, file, ad, setting, analytics
| Operations: view, create, edit, delete
*/
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {

    // Dashboard (accessible to all authenticated users)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile (accessible to all authenticated users — no permission needed)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');

    // 2FA setup (per user, no extra permission needed)
    Route::get('/two-factor/setup', [TwoFactorController::class, 'setup'])->name('two-factor.setup');
    Route::post('/two-factor/enable', [TwoFactorController::class, 'enable'])->name('two-factor.enable');
    Route::post('/two-factor/disable', [TwoFactorController::class, 'disable'])->name('two-factor.disable');

    // Blog Management Routes
    Route::middleware('check.permission:blog.view')->group(function () {
        Route::get('blogs', [BlogController::class, 'index'])->name('blogs.index');
        Route::get('blogs/export', [BlogController::class, 'export'])->name('blogs.export');
    });
    Route::middleware('check.permission:blog.create')->group(function () {
        Route::get('blogs/create', [BlogController::class, 'create'])->name('blogs.create');
        Route::post('blogs', [BlogController::class, 'store'])->name('blogs.store');
    });
    Route::middleware('check.permission:blog.edit')->group(function () {
        Route::get('blogs/{blog}/edit', [BlogController::class, 'edit'])->name('blogs.edit');
        Route::match(['put', 'patch'], 'blogs/{blog}', [BlogController::class, 'update'])->name('blogs.update');
        Route::post('blogs/{id}/restore', [BlogController::class, 'restore'])->name('blogs.restore');
        Route::post('blogs/bulk', [BlogController::class, 'bulk'])->name('blogs.bulk');
        Route::post('blogs/{blog}/autosave', [BlogController::class, 'autosave'])->name('blogs.autosave');
    });
    Route::middleware('check.permission:blog.delete')->group(function () {
        Route::delete('blogs/{blog}', [BlogController::class, 'destroy'])->name('blogs.destroy');
        Route::delete('blogs/{id}/force-delete', [BlogController::class, 'forceDelete'])->name('blogs.force-delete');
    });
    Route::get('blogs/{blog}', [BlogController::class, 'show'])
        ->middleware('check.permission:blog.view')
        ->name('blogs.show');

    // Page Management Routes
    Route::middleware('check.permission:page.view')->group(function () {
        Route::get('pages', [PageController::class, 'index'])->name('pages.index');
    });
    Route::middleware('check.permission:page.create')->group(function () {
        Route::get('pages/create', [PageController::class, 'create'])->name('pages.create');
        Route::post('pages', [PageController::class, 'store'])->name('pages.store');
    });
    Route::middleware('check.permission:page.edit')->group(function () {
        Route::get('pages/{page}/edit', [PageController::class, 'edit'])->name('pages.edit');
        Route::match(['put', 'patch'], 'pages/{page}', [PageController::class, 'update'])->name('pages.update');
        Route::post('pages/{id}/restore', [PageController::class, 'restore'])->name('pages.restore');
        Route::post('pages/bulk', [PageController::class, 'bulk'])->name('pages.bulk');
    });
    Route::middleware('check.permission:page.delete')->group(function () {
        Route::delete('pages/{page}', [PageController::class, 'destroy'])->name('pages.destroy');
        Route::delete('pages/{id}/force-delete', [PageController::class, 'forceDelete'])->name('pages.force-delete');
    });
    Route::get('pages/{page}', [PageController::class, 'show'])
        ->middleware('check.permission:page.view')
        ->name('pages.show');

    // Category Management Routes
    Route::middleware('check.permission:category.view')->group(function () {
        Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
    });
    Route::middleware('check.permission:category.create')->group(function () {
        Route::get('categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
    });
    Route::middleware('check.permission:category.edit')->group(function () {
        Route::get('categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::match(['put', 'patch'], 'categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    });
    Route::delete('categories/{category}', [CategoryController::class, 'destroy'])
        ->middleware('check.permission:category.delete')
        ->name('categories.destroy');
    Route::get('categories/{category}', [CategoryController::class, 'show'])
        ->middleware('check.permission:category.view')
        ->name('categories.show');

    // Menu Builder Routes
    Route::middleware('check.permission:menu.view')->group(function () {
        Route::get('menus', [MenuController::class, 'index'])->name('menus.index');
    });
    Route::middleware('check.permission:menu.create')->group(function () {
        Route::get('menus/create', [MenuController::class, 'create'])->name('menus.create');
        Route::post('menus', [MenuController::class, 'store'])->name('menus.store');
    });
    Route::middleware('check.permission:menu.edit')->group(function () {
        Route::get('menus/{menu}/edit', [MenuController::class, 'edit'])->name('menus.edit');
        Route::match(['put', 'patch'], 'menus/{menu}', [MenuController::class, 'update'])->name('menus.update');
        Route::put('menus/{menu}/items', [MenuController::class, 'saveItems'])->name('menus.save-items');
    });
    Route::delete('menus/{menu}', [MenuController::class, 'destroy'])
        ->middleware('check.permission:menu.delete')
        ->name('menus.destroy');
    Route::get('menus/{menu}', [MenuController::class, 'show'])
        ->middleware('check.permission:menu.view')
        ->name('menus.show');

    // File Manager Routes
    Route::middleware('check.permission:file.view')->group(function () {
        Route::get('files', [FileManagerController::class, 'index'])->name('files.index');
        Route::get('files/search', [FileManagerController::class, 'search'])->name('files.search');
        Route::get('files/picker', [FileManagerController::class, 'picker'])->name('files.picker');
    });
    Route::middleware('check.permission:file.create')->group(function () {
        Route::post('files/upload', [FileManagerController::class, 'upload'])->name('files.upload');
        Route::post('files/folders', [FileManagerController::class, 'createFolder'])->name('files.create-folder');
    });
    Route::delete('files/{media}', [FileManagerController::class, 'destroy'])
        ->middleware('check.permission:file.delete')
        ->name('files.destroy');
    Route::delete('files/folders/{folder}', [FileManagerController::class, 'destroyFolder'])
        ->middleware('check.permission:file.delete')
        ->name('files.destroy-folder');
    Route::patch('files/{media}', [FileManagerController::class, 'update'])
        ->middleware('check.permission:file.edit')
        ->name('files.update');
    Route::post('files/{media}/crop', [FileManagerController::class, 'crop'])
        ->middleware('check.permission:file.edit')
        ->name('files.crop');

    // Ad Management Routes
    Route::middleware('check.permission:ad.view')->group(function () {
        Route::get('ads', [AdController::class, 'index'])->name('ads.index');
    });
    Route::middleware('check.permission:ad.create')->group(function () {
        Route::get('ads/create', [AdController::class, 'create'])->name('ads.create');
        Route::post('ads', [AdController::class, 'store'])->name('ads.store');
    });
    Route::middleware('check.permission:ad.edit')->group(function () {
        Route::get('ads/{ad}/edit', [AdController::class, 'edit'])->name('ads.edit');
        Route::match(['put', 'patch'], 'ads/{ad}', [AdController::class, 'update'])->name('ads.update');
        Route::post('ads/{ad}/toggle', [AdController::class, 'toggleStatus'])->name('ads.toggle');
    });
    Route::delete('ads/{ad}', [AdController::class, 'destroy'])
        ->middleware('check.permission:ad.delete')
        ->name('ads.destroy');
    Route::get('ads/{ad}', [AdController::class, 'show'])
        ->middleware('check.permission:ad.view')
        ->name('ads.show');

    // Settings Routes
    Route::middleware('check.permission:setting.view')->group(function () {
        Route::get('settings/{group}', [SettingController::class, 'edit'])->name('settings.edit');
    });
    Route::put('settings/{group}', [SettingController::class, 'update'])
        ->middleware('check.permission:setting.edit')
        ->name('settings.update');
    Route::post('settings/email/test', [SettingController::class, 'testEmail'])
        ->middleware(['check.permission:setting.edit', 'throttle:5,1'])
        ->name('settings.email-test');

    // User Management Routes
    Route::middleware('check.permission:user.view')->group(function () {
        Route::get('users', [UserController::class, 'index'])->name('users.index');
    });
    Route::middleware('check.permission:user.create')->group(function () {
        Route::get('users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('users', [UserController::class, 'store'])->name('users.store');
    });
    Route::middleware('check.permission:user.edit')->group(function () {
        Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::match(['put', 'patch'], 'users/{user}', [UserController::class, 'update'])->name('users.update');
    });
    Route::delete('users/{user}', [UserController::class, 'destroy'])
        ->middleware('check.permission:user.delete')
        ->name('users.destroy');
    Route::get('users/{user}', [UserController::class, 'show'])
        ->middleware('check.permission:user.view')
        ->name('users.show');

    // Role Management Routes
    Route::middleware('check.permission:role.view')->group(function () {
        Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
    });
    Route::middleware('check.permission:role.create')->group(function () {
        Route::get('roles/create', [RoleController::class, 'create'])->name('roles.create');
        Route::post('roles', [RoleController::class, 'store'])->name('roles.store');
    });
    Route::middleware('check.permission:role.edit')->group(function () {
        Route::get('roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
        Route::match(['put', 'patch'], 'roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    });
    Route::delete('roles/{role}', [RoleController::class, 'destroy'])
        ->middleware('check.permission:role.delete')
        ->name('roles.destroy');
    Route::get('roles/{role}', [RoleController::class, 'show'])
        ->middleware('check.permission:role.view')
        ->name('roles.show');

    // Analytics Routes
    Route::middleware('check.permission:analytics.view')->group(function () {
        Route::get('analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    });

    // SEO Tools
    Route::middleware('check.permission:setting.view')->group(function () {
        Route::get('seo', [SeoToolController::class, 'index'])->name('seo.index');
        Route::get('seo/preview', [SeoToolController::class, 'preview'])->name('seo.preview');
    });
    Route::post('seo/regenerate-sitemap', [SeoToolController::class, 'regenerateSitemap'])
        ->middleware('check.permission:setting.edit')
        ->name('seo.regenerate-sitemap');

    // Maintenance Actions
    Route::post('maintenance/clear-cache', [MaintenanceController::class, 'clearCache'])
        ->middleware('check.permission:setting.edit')
        ->name('maintenance.clear-cache');

    // Contact Submissions
    Route::middleware('check.permission:contact.view')->group(function () {
        Route::get('contacts', [ContactSubmissionController::class, 'index'])->name('contacts.index');
        Route::get('contacts/{contact}', [ContactSubmissionController::class, 'show'])->name('contacts.show');
    });
    Route::middleware('check.permission:contact.edit')->group(function () {
        Route::post('contacts/{contact}/toggle-read', [ContactSubmissionController::class, 'toggleRead'])->name('contacts.toggle-read');
        Route::post('contacts/{contact}/reply', [ContactSubmissionController::class, 'reply'])->name('contacts.reply');
    });
    Route::delete('contacts/{contact}', [ContactSubmissionController::class, 'destroy'])
        ->middleware('check.permission:contact.delete')
        ->name('contacts.destroy');

    // Audit Log
    Route::middleware('check.permission:setting.view')->group(function () {
        Route::get('audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
    });

    // Comments moderation
    Route::middleware('check.permission:blog.view')->group(function () {
        Route::get('comments', [App\Http\Controllers\Admin\CommentController::class, 'index'])->name('comments.index');
    });
    Route::middleware('check.permission:blog.edit')->group(function () {
        Route::post('comments/{comment}/approve', [App\Http\Controllers\Admin\CommentController::class, 'approve'])->name('comments.approve');
        Route::post('comments/{comment}/spam', [App\Http\Controllers\Admin\CommentController::class, 'markSpam'])->name('comments.spam');
        Route::post('comments/bulk', [App\Http\Controllers\Admin\CommentController::class, 'bulk'])->name('comments.bulk');
    });
    Route::delete('comments/{comment}', [App\Http\Controllers\Admin\CommentController::class, 'destroy'])
        ->middleware('check.permission:blog.delete')
        ->name('comments.destroy');

    // Newsletter subscribers
    Route::middleware('check.permission:newsletter.view')->group(function () {
        Route::get('newsletter', [App\Http\Controllers\Admin\NewsletterController::class, 'index'])->name('newsletter.index');
        Route::get('newsletter/export', [App\Http\Controllers\Admin\NewsletterController::class, 'export'])->name('newsletter.export');
        Route::get('newsletter/campaigns', [NewsletterCampaignController::class, 'index'])->name('newsletter.campaigns.index');
    });
    Route::middleware('check.permission:newsletter.create')->group(function () {
        // 'create' MUST be registered before {campaign} wildcard
        Route::get('newsletter/campaigns/create', [NewsletterCampaignController::class, 'create'])->name('newsletter.campaigns.create');
        Route::post('newsletter/campaigns', [NewsletterCampaignController::class, 'store'])->name('newsletter.campaigns.store');
        Route::post('newsletter/campaigns/{campaign}/send', [NewsletterCampaignController::class, 'send'])->name('newsletter.campaigns.send')->middleware('throttle:3,1');
    });
    Route::middleware('check.permission:newsletter.view')->group(function () {
        // {campaign} wildcard AFTER static 'create' route
        Route::get('newsletter/campaigns/{campaign}', [NewsletterCampaignController::class, 'show'])->name('newsletter.campaigns.show');
    });
    Route::delete('newsletter/campaigns/{campaign}', [NewsletterCampaignController::class, 'destroy'])
        ->middleware('check.permission:newsletter.delete')
        ->name('newsletter.campaigns.destroy');
    Route::delete('newsletter/{subscriber}', [App\Http\Controllers\Admin\NewsletterController::class, 'destroy'])
        ->middleware('check.permission:newsletter.delete')
        ->name('newsletter.destroy');

    // Export/Import Tools
    Route::middleware('check.permission:setting.edit')->group(function () {
        Route::get('tools/export-import', [\App\Http\Controllers\Admin\ExportImportController::class, 'index'])->name('tools.export-import');
        Route::post('tools/export/blogs', [\App\Http\Controllers\Admin\ExportImportController::class, 'exportBlogs'])->name('tools.export.blogs');
        Route::post('tools/export/pages', [\App\Http\Controllers\Admin\ExportImportController::class, 'exportPages'])->name('tools.export.pages');
        Route::post('tools/import/blogs', [\App\Http\Controllers\Admin\ExportImportController::class, 'importBlogs'])->name('tools.import.blogs');
        Route::post('tools/import/pages', [\App\Http\Controllers\Admin\ExportImportController::class, 'importPages'])->name('tools.import.pages');
        Route::get('tools/export/download', [\App\Http\Controllers\Admin\ExportImportController::class, 'download'])->name('tools.export.download');
        Route::delete('tools/export/delete', [\App\Http\Controllers\Admin\ExportImportController::class, 'deleteExport'])->name('tools.export.delete');
    });

    // Page Builder
    Route::middleware('check.permission:page.edit')->group(function () {
        Route::get('page-builder', [\App\Http\Controllers\Admin\PageBuilderController::class, 'index'])->name('page-builder.index');
        Route::get('pages/{page}/builder', [\App\Http\Controllers\Admin\PageBuilderController::class, 'edit'])->name('page-builder.edit');
        Route::post('pages/{page}/builder/save', [\App\Http\Controllers\Admin\PageBuilderController::class, 'save'])->name('page-builder.save');
        Route::get('pages/{page}/builder/preview', [\App\Http\Controllers\Admin\PageBuilderController::class, 'preview'])->name('page-builder.preview');
        Route::get('page-builder/components', [\App\Http\Controllers\Admin\PageBuilderController::class, 'components'])->name('page-builder.components');
        Route::delete('page-builder/components/{component}', [\App\Http\Controllers\Admin\PageBuilderController::class, 'deleteComponent'])->name('page-builder.delete-component');
        Route::get('pages/{page}/builder/grapesjs', [\App\Http\Controllers\Admin\PageBuilderController::class, 'grapesjsEdit'])->name('pages.grapesjs');
        Route::get('pages/{page}/builder/grapesjs/load', [\App\Http\Controllers\Admin\PageBuilderController::class, 'grapesjsLoad'])->name('pages.grapesjs.load');
        Route::post('pages/{page}/builder/grapesjs/save', [\App\Http\Controllers\Admin\PageBuilderController::class, 'grapesjsSave'])->name('pages.grapesjs.save');
    });

    // Notifications (AJAX endpoint - needs session/web middleware, not API)
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
});

/*
|--------------------------------------------------------------------------
| Static Page Routes (named — must be before the catch-all)
|--------------------------------------------------------------------------
*/
Route::get('/layanan', [PageFrontController::class, 'show'])->defaults('slug', 'layanan')->name('page.layanan');
Route::get('/tentang-kami', [PageFrontController::class, 'show'])->defaults('slug', 'tentang-kami')->name('page.tentang-kami');
Route::get('/kontak', [PageFrontController::class, 'show'])->defaults('slug', 'kontak')->name('page.kontak');
Route::get('/faq', [PageFrontController::class, 'show'])->defaults('slug', 'faq')->name('page.faq');
Route::get('/kebijakan-privasi', [PageFrontController::class, 'show'])->defaults('slug', 'kebijakan-privasi')->name('page.kebijakan-privasi');
Route::get('/syarat-ketentuan', [PageFrontController::class, 'show'])->defaults('slug', 'syarat-ketentuan')->name('page.syarat-ketentuan');

/*
|--------------------------------------------------------------------------
| Static Page Route (catch-all, must be LAST)
|--------------------------------------------------------------------------
*/
Route::get('/{slug}', [PageFrontController::class, 'show'])->name('page.show')->where('slug', '(?!admin|login|logout|register|search|sitemap|robots|feed|rss|newsletter)[a-z0-9][a-z0-9\-]*');
