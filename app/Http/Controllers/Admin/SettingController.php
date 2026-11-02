<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\MailConfigService;
use App\Traits\RespondsWithError;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class SettingController extends Controller
{
    use RespondsWithError;

    /**
     * Valid setting groups.
     */
    protected array $validGroups = ['general', 'homepage', 'social', 'seo', 'footer', 'maintenance', 'email'];

    /**
     * Show settings form for a group.
     */
    public function edit(string $group)
    {
        if (! in_array($group, $this->validGroups)) {
            abort(404);
        }

        $settings = Setting::getGroup($group);

        return view('admin.settings.edit', compact('group', 'settings'));
    }

    /**
     * Validate and save settings for a group.
     */
    public function update(Request $request, string $group)
    {
        if (! in_array($group, $this->validGroups)) {
            abort(404);
        }

        $validated = $this->validateGroup($request, $group);

        // Handle file uploads
        if ($group === 'general') {
            $validated = $this->handleFileUploads($request, $validated);
            // Checkbox: simpan '0' jika tidak dicentang
            $validated['whatsapp_float_enabled'] = $request->has('whatsapp_float_enabled') ? '1' : '0';
        }

        if ($group === 'homepage') {
            $validated = $this->handleHomepageFileUploads($request, $validated);
        }

        // Save each setting
        foreach ($validated as $key => $value) {
            Setting::updateOrCreate(
                ['group' => $group, 'key' => $key],
                ['value' => $value]
            );
        }

        // If email settings changed, apply to runtime config immediately
        // (next request will load via boot, but the test-email button below
        // calls a separate endpoint; this is for safety on multi-form pages)
        if ($group === 'email') {
            app(MailConfigService::class)->apply();
        }

        return redirect()->route('admin.settings.edit', $group)
            ->with('success', 'Pengaturan berhasil disimpan.');
    }

    /**
     * Send a test email using current SMTP settings to verify configuration.
     */
    public function testEmail(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        // Apply latest config (in case settings just saved this request)
        app(MailConfigService::class)->apply();

        $siteName = Setting::get('site_name', 'general') ?? config('app.name');

        try {
            Mail::raw(
                "Halo!\n\nIni adalah email test dari {$siteName}.\n\nJika Anda menerima email ini, konfigurasi SMTP berfungsi dengan benar.\n\n— {$siteName}",
                function ($message) use ($validated, $siteName) {
                    $message->to($validated['email'])
                        ->subject('Test Email dari '.$siteName.' - '.now()->format('H:i'));
                }
            );
        } catch (\Throwable $e) {
            return $this->respondError('Gagal mengirim email: '.$e->getMessage());
        }

        return back()->with('success', 'Test email berhasil dikirim ke '.$validated['email'].'. Periksa inbox (atau folder spam).');
    }

    /**
     * Validate request data based on the group.
     */
    protected function validateGroup(Request $request, string $group): array
    {
        $rules = match ($group) {
            'general' => [
                'site_name'              => ['nullable', 'max:100'],
                'tagline'                => ['nullable', 'max:255'],
                'logo'                   => ['nullable', 'file', 'mimes:png,jpg,jpeg,svg,webp', 'max:5120'],
                'favicon'                => ['nullable', 'file', 'mimetypes:image/x-icon,image/vnd.microsoft.icon,image/png,image/jpeg,image/svg+xml', 'max:2048'],
                'contact_email'          => ['nullable', 'email', 'max:255'],
                'contact_phone'          => ['nullable', 'max:20'],
                'navbar_cta_text'        => ['nullable', 'max:50'],
                'navbar_cta_url'         => ['nullable', 'max:255'],
                'whatsapp_float_enabled' => ['nullable'],
                'whatsapp_number'        => ['nullable', 'max:20', 'regex:/^[0-9]+$/'],
                'whatsapp_text'          => ['nullable', 'max:200'],
                'maps_lat'               => ['nullable', 'max:20'],
                'maps_lng'               => ['nullable', 'max:20'],
                'maps_zoom'              => ['nullable', 'integer', 'min:1', 'max:20'],
                'office_address'         => ['nullable', 'max:255'],
            ],
            'homepage' => [
                'hero_title' => ['nullable', 'max:255'],
                'hero_subtitle' => ['nullable', 'max:500'],
                'hero_cta_text' => ['nullable', 'max:50'],
                'hero_cta_url' => ['nullable', 'max:255'],
                'hero_image' => ['nullable', 'file', 'mimes:png,jpg,jpeg,webp', 'max:5120'],
                'service_1_title' => ['nullable', 'max:100'],
                'service_1_description' => ['nullable', 'max:255'],
                'service_2_title' => ['nullable', 'max:100'],
                'service_2_description' => ['nullable', 'max:255'],
                'service_3_title' => ['nullable', 'max:100'],
                'service_3_description' => ['nullable', 'max:255'],
                'service_4_title' => ['nullable', 'max:100'],
                'service_4_description' => ['nullable', 'max:255'],
                'about_title' => ['nullable', 'max:255'],
                'about_description' => ['nullable', 'max:1000'],
                'about_image' => ['nullable', 'file', 'mimes:png,jpg,jpeg,webp', 'max:5120'],
                'cta_title' => ['nullable', 'max:255'],
                'cta_description' => ['nullable', 'max:500'],
                'cta_button_text' => ['nullable', 'max:50'],
                'cta_button_url' => ['nullable', 'max:255'],
            ],
            'social' => [
                'facebook_url' => ['nullable', 'url', 'max:255'],
                'instagram_url' => ['nullable', 'url', 'max:255'],
                'twitter_url' => ['nullable', 'url', 'max:255'],
                'youtube_url' => ['nullable', 'url', 'max:255'],
                'tiktok_url' => ['nullable', 'url', 'max:255'],
            ],
            'seo' => [
                'meta_title_default' => ['nullable', 'max:70'],
                'meta_description_default' => ['nullable', 'max:160'],
                'ga_measurement_id' => ['nullable', 'regex:/^G-[A-Za-z0-9]{1,15}$/'],
                'robots_txt' => ['nullable', 'string', 'max:5000'],
            ],
            'footer' => [
                'copyright_text' => ['nullable', 'max:255'],
                'additional_content' => ['nullable', 'max:1000'],
            ],
            'maintenance' => [
                'maintenance_mode' => ['required', 'in:0,1'],
            ],
            'email' => [
                'mail_mailer' => ['nullable', 'in:smtp,log,sendmail,array'],
                'mail_host' => ['nullable', 'string', 'max:255'],
                'mail_port' => ['nullable', 'integer', 'min:1', 'max:65535'],
                'mail_username' => ['nullable', 'string', 'max:255'],
                'mail_password' => ['nullable', 'string', 'max:255'],
                'mail_encryption' => ['nullable', 'in:tls,ssl,none,'],
                'mail_from_address' => ['nullable', 'email', 'max:255'],
                'mail_from_name' => ['nullable', 'string', 'max:100'],
            ],
            default => [],
        };

        $validated = $request->validate($rules);

        // Remove file fields from validated data (handled separately)
        if ($group === 'general') {
            unset($validated['logo'], $validated['favicon']);
        }

        if ($group === 'homepage') {
            unset($validated['hero_image'], $validated['about_image']);
        }

        return $validated;
    }

    /**
     * Handle file uploads for logo and favicon.
     * Uploading a new file automatically replaces the old one.
     */
    protected function handleFileUploads(Request $request, array $validated): array
    {
        if ($request->hasFile('logo')) {
            $oldLogo = Setting::get('logo', 'general');
            if ($oldLogo) {
                Storage::disk('public')->delete($oldLogo);
            }
            $validated['logo'] = $request->file('logo')->store('settings', 'public');
        }

        if ($request->hasFile('favicon')) {
            $oldFavicon = Setting::get('favicon', 'general');
            if ($oldFavicon) {
                Storage::disk('public')->delete($oldFavicon);
            }
            // Delete the entire favicon/ directory so no stale files remain
            Storage::disk('public')->deleteDirectory('favicon');
            $validated['favicon'] = $this->resizeFavicon($request->file('favicon'));
        }

        return $validated;
    }

    /**
     * Handle file uploads for homepage settings.
     * Uploading a new file automatically replaces the old one.
     */
    protected function handleHomepageFileUploads(Request $request, array $validated): array
    {
        if ($request->hasFile('hero_image')) {
            $oldImage = Setting::get('hero_image', 'homepage');
            if ($oldImage) {
                Storage::disk('public')->delete($oldImage);
            }
            $validated['hero_image'] = $request->file('hero_image')->store('settings/homepage', 'public');
        }

        if ($request->hasFile('about_image')) {
            $oldImage = Setting::get('about_image', 'homepage');
            if ($oldImage) {
                Storage::disk('public')->delete($oldImage);
            }
            $validated['about_image'] = $request->file('about_image')->store('settings/homepage', 'public');
        }

        return $validated;
    }

    /**
     * Resize an uploaded favicon to multiple sizes using Intervention Image v3.
     * Generates 16×16, 32×32, 180×180, 192×192 variants in storage/app/public/favicon/.
     * Falls back to GD native for all four sizes if Intervention Image fails.
     * Falls back to storing the original file in settings/ if both methods fail.
     *
     * Returns the path of the 32×32 variant (used as the DB reference value).
     */
    private function resizeFavicon(\Illuminate\Http\UploadedFile $file): string
    {
        $faviconDir = Storage::disk('public')->path('favicon');
        if (! is_dir($faviconDir)) {
            mkdir($faviconDir, 0755, true);
        }

        $sizes = [
            'favicon/favicon-16.png'  => 16,
            'favicon/favicon-32.png'  => 32,
            'favicon/favicon-180.png' => 180,
            'favicon/favicon-192.png' => 192,
        ];

        // ── Intervention Image v3 ─────────────────────────────────────────────
        try {
            $manager = new ImageManager(new Driver());

            foreach ($sizes as $relativePath => $size) {
                $outputPath = Storage::disk('public')->path($relativePath);
                // Re-read on every iteration so the image object is not mutated between passes
                $manager->read($file->path())
                    ->cover($size, $size)
                    ->toPng()
                    ->save($outputPath);
            }

            // Copy to public/favicon.ico so browsers that auto-request /favicon.ico work
            try {
                copy(
                    Storage::disk('public')->path('favicon/favicon-32.png'),
                    public_path('favicon.ico')
                );
            } catch (\Throwable $copyErr) {
                Log::error('resizeFavicon: failed to copy favicon.ico: ' . $copyErr->getMessage()
                    . ' at ' . $copyErr->getFile() . ':' . $copyErr->getLine());
            }

            return 'favicon/favicon-32.png';
        } catch (\Throwable $e) {
            Log::error('resizeFavicon: Intervention Image failed: ' . $e->getMessage()
                . ' at ' . $e->getFile() . ':' . $e->getLine());
        }

        // ── GD native fallback ────────────────────────────────────────────────
        if (! extension_loaded('gd')) {
            Log::error('resizeFavicon: GD extension not loaded, storing original file.');
            return $file->store('settings', 'public');
        }

        try {
            $mime = $file->getMimeType();
            $src  = match (true) {
                str_contains($mime, 'png')  => imagecreatefrompng($file->path()),
                str_contains($mime, 'jpeg') => imagecreatefromjpeg($file->path()),
                str_contains($mime, 'gif')  => imagecreatefromgif($file->path()),
                str_contains($mime, 'webp') => imagecreatefromwebp($file->path()),
                default                     => null,
            };
        } catch (\Throwable $e) {
            Log::error('resizeFavicon: GD failed to open source image: ' . $e->getMessage()
                . ' at ' . $e->getFile() . ':' . $e->getLine());
            return $file->store('settings', 'public');
        }

        if (! $src) {
            Log::error('resizeFavicon: GD could not create image resource (unsupported mime: ' . $file->getMimeType() . ').');
            return $file->store('settings', 'public');
        }

        $srcW = imagesx($src);
        $srcH = imagesy($src);

        try {
            foreach ($sizes as $relativePath => $size) {
                $outputPath = Storage::disk('public')->path($relativePath);

                $dst = imagecreatetruecolor($size, $size);
                imagealphablending($dst, false);
                imagesavealpha($dst, true);
                $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
                imagefill($dst, 0, 0, $transparent);

                // Cover: crop to square centre then resample
                $minDim = min($srcW, $srcH);
                $cropX  = (int) (($srcW - $minDim) / 2);
                $cropY  = (int) (($srcH - $minDim) / 2);
                imagecopyresampled($dst, $src, 0, 0, $cropX, $cropY, $size, $size, $minDim, $minDim);

                imagepng($dst, $outputPath, 9);
                imagedestroy($dst);
            }

            // Copy to public/favicon.ico
            try {
                $favicon32 = Storage::disk('public')->path('favicon/favicon-32.png');
                copy($favicon32, public_path('favicon.ico'));
            } catch (\Throwable $copyErr) {
                Log::error('resizeFavicon: GD fallback failed to copy favicon.ico: ' . $copyErr->getMessage()
                    . ' at ' . $copyErr->getFile() . ':' . $copyErr->getLine());
            }
        } catch (\Throwable $e) {
            Log::error('resizeFavicon: GD resize failed: ' . $e->getMessage()
                . ' at ' . $e->getFile() . ':' . $e->getLine());
            imagedestroy($src);
            return $file->store('settings', 'public');
        }

        imagedestroy($src);

        return 'favicon/favicon-32.png';
    }
}
