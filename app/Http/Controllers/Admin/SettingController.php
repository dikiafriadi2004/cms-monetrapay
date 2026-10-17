<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\MailConfigService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
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

        try {
            Mail::raw(
                "Halo!\n\nIni adalah email test dari MonetraPay CMS.\n\nJika Anda menerima email ini, konfigurasi SMTP berfungsi dengan benar.\n\n— MonetraPay",
                function ($message) use ($validated) {
                    $message->to($validated['email'])
                        ->subject('Test Email dari MonetraPay CMS - '.now()->format('H:i'));
                }
            );
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal mengirim email: '.$e->getMessage())->withInput();
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
                'site_name' => ['nullable', 'max:100'],
                'tagline' => ['nullable', 'max:255'],
                'logo' => ['nullable', 'file', 'mimes:png,jpg,jpeg,svg,webp', 'max:5120'],
                'favicon' => ['nullable', 'file', 'mimetypes:image/x-icon,image/vnd.microsoft.icon,image/png,image/jpeg,image/svg+xml', 'max:2048'],
                'contact_email' => ['nullable', 'email', 'max:255'],
                'contact_phone' => ['nullable', 'max:20'],
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
            $validated['favicon'] = $request->file('favicon')->store('settings', 'public');
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
}
