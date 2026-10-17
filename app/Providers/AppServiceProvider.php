<?php

namespace App\Providers;

use App\Models\Ad;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Media;
use App\Models\Page;
use App\Models\Setting;
use App\Models\User;
use App\Observers\AuditableObserver;
use App\Observers\BlogObserver;
use App\Observers\ContentCacheInvalidationObserver;
use App\Observers\SitemapInvalidationObserver;
use App\Services\MailConfigService;
use App\Support\RetryingFilesystem;
use App\View\Composers\FrontendComposer;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('files', fn () => new RetryingFilesystem);
        $this->app->alias('files', Filesystem::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Apply mail config from admin Settings (overrides .env defaults)
        try {
            app(MailConfigService::class)->apply();
        } catch (\Throwable $e) {
            // ignore — happens before migrations run (e.g. fresh install)
        }

        // Force HTTPS scheme in production (e.g. behind a reverse proxy)
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Custom password reset notification — use our branded email template
        ResetPassword::createUrlUsing(function ($notifiable, $token) {
            return url(route('password.reset', ['token' => $token, 'email' => $notifiable->email], false));
        });

        ResetPassword::toMailUsing(function ($notifiable, $token) {
            $url = url(route('password.reset', ['token' => $token, 'email' => $notifiable->email], false));
            $siteName = Setting::get('site_name', 'general', 'MonetraPay');
            app(MailConfigService::class)->apply();

            return (new MailMessage)
                ->subject("Reset Password — {$siteName}")
                ->view('emails.reset-password', ['url' => $url]);
        });

        Blog::observe(BlogObserver::class);

        // Auto-regenerate sitemap.xml whenever published content changes
        Blog::observe(SitemapInvalidationObserver::class);
        Page::observe(SitemapInvalidationObserver::class);
        Category::observe(SitemapInvalidationObserver::class);

        // Audit log for sensitive models
        Blog::observe(AuditableObserver::class);
        Page::observe(AuditableObserver::class);
        Category::observe(AuditableObserver::class);
        User::observe(AuditableObserver::class);
        Setting::observe(AuditableObserver::class);
        Ad::observe(AuditableObserver::class);
        Media::observe(AuditableObserver::class);

        // Content cache auto-invalidation when frontend-visible content changes
        Blog::observe(ContentCacheInvalidationObserver::class);
        Category::observe(ContentCacheInvalidationObserver::class);
        Page::observe(ContentCacheInvalidationObserver::class);

        // Use Tailwind pagination view by default
        Paginator::defaultView('vendor.pagination.tailwind');
        Paginator::defaultSimpleView('vendor.pagination.tailwind');

        // Share common data with all frontend layout views
        View::composer('frontend.layouts.app', FrontendComposer::class);
    }
}
