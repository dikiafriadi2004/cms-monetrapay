<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DownloadPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gjs_html = <<<'HTML'
<!-- Hero Section -->
<section style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: #fff; padding: 80px 20px; text-align: center;">
  <div style="max-width: 800px; margin: 0 auto;">
    <h1 style="font-size: 2.5rem; font-weight: 800; margin-bottom: 16px; line-height: 1.2;">Download Aplikasi</h1>
    <p style="font-size: 1.125rem; color: #94a3b8; margin-bottom: 32px;">Unduh aplikasi kami dan nikmati kemudahan layanan digital di genggaman tangan Anda</p>
    <div style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap; margin-bottom: 24px;">
      <a href="#" style="display: inline-flex; align-items: center; gap: 10px; background: #fff; color: #0f172a; padding: 14px 28px; border-radius: 12px; text-decoration: none; font-weight: 700; font-size: 1rem;">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/></svg>
        Download di App Store
      </a>
      <a href="#" style="display: inline-flex; align-items: center; gap: 10px; background: #3D9E7A; color: #fff; padding: 14px 28px; border-radius: 12px; text-decoration: none; font-weight: 700; font-size: 1rem;">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M3.18 23.76c.3.17.64.19.96.08l12.45-7.19-2.63-2.63-10.78 9.74zM.37 1.08C.14 1.42 0 1.88 0 2.46v19.08c0 .58.14 1.04.37 1.38l.07.07 10.69-10.69v-.25L.44 1.01l-.07.07zM20.69 10.37l-2.73-1.57-2.95 2.95 2.95 2.95 2.75-1.59c.78-.45.78-1.19-.02-1.74zM3.18.24L15.96 7.57 13.33 10.2.37 1.08C.68.59 1.14.33 1.57.33c.22 0 .44.06.64.17l.97-.26z"/></svg>
        Download di Google Play
      </a>
    </div>
    <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
      <span style="background: #3D9E7A; color: #fff; padding: 6px 16px; border-radius: 999px; font-size: 0.875rem; font-weight: 600;">✓ Gratis</span>
      <span style="background: rgba(255,255,255,0.1); color: #e2e8f0; padding: 6px 16px; border-radius: 999px; font-size: 0.875rem;">Tersedia di iOS &amp; Android</span>
    </div>
  </div>
</section>

<!-- Fitur Aplikasi -->
<section style="background: #fff; padding: 64px 20px;">
  <div style="max-width: 960px; margin: 0 auto;">
    <h2 style="text-align: center; font-size: 2rem; font-weight: 800; color: #0f172a; margin-bottom: 8px;">Fitur Aplikasi</h2>
    <p style="text-align: center; color: #64748b; margin-bottom: 48px;">Semua yang Anda butuhkan dalam satu genggaman</p>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 24px;">
      <div style="padding: 28px 20px; border-radius: 16px; border: 2px solid #e2e8f0; text-align: center;">
        <div style="font-size: 2.5rem; margin-bottom: 12px;">📱</div>
        <h3 style="font-size: 1rem; font-weight: 700; color: #0f172a;">Antarmuka Intuitif</h3>
        <p style="color: #64748b; font-size: 0.85rem; margin-top: 6px;">Desain yang mudah dipahami untuk semua kalangan</p>
      </div>
      <div style="padding: 28px 20px; border-radius: 16px; border: 2px solid #e2e8f0; text-align: center;">
        <div style="font-size: 2.5rem; margin-bottom: 12px;">⚡</div>
        <h3 style="font-size: 1rem; font-weight: 700; color: #0f172a;">Performa Cepat</h3>
        <p style="color: #64748b; font-size: 0.85rem; margin-top: 6px;">Responsif dan ringan di semua perangkat</p>
      </div>
      <div style="padding: 28px 20px; border-radius: 16px; border: 2px solid #e2e8f0; text-align: center;">
        <div style="font-size: 2.5rem; margin-bottom: 12px;">🔒</div>
        <h3 style="font-size: 1rem; font-weight: 700; color: #0f172a;">Keamanan Terjamin</h3>
        <p style="color: #64748b; font-size: 0.85rem; margin-top: 6px;">Data Anda terlindungi dengan enkripsi terkini</p>
      </div>
      <div style="padding: 28px 20px; border-radius: 16px; border: 2px solid #e2e8f0; text-align: center;">
        <div style="font-size: 2.5rem; margin-bottom: 12px;">🔄</div>
        <h3 style="font-size: 1rem; font-weight: 700; color: #0f172a;">Update Berkala</h3>
        <p style="color: #64748b; font-size: 0.85rem; margin-top: 6px;">Selalu diperbarui dengan fitur dan perbaikan terbaru</p>
      </div>
    </div>
  </div>
</section>

<!-- CTA Final -->
<section style="background: linear-gradient(135deg, #3D9E7A 0%, #2d7a5f 100%); padding: 80px 20px; text-align: center;">
  <div style="max-width: 640px; margin: 0 auto;">
    <h2 style="font-size: 2rem; font-weight: 800; color: #fff; margin-bottom: 12px;">Mulai Gunakan Sekarang</h2>
    <p style="color: rgba(255,255,255,0.85); font-size: 1.1rem; margin-bottom: 36px;">Unduh aplikasi kami dan rasakan kemudahannya</p>
    <div style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
      <a href="#" style="display: inline-flex; align-items: center; gap: 10px; background: #fff; color: #0f172a; padding: 16px 32px; border-radius: 12px; text-decoration: none; font-weight: 700; font-size: 1rem;">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/></svg>
        App Store
      </a>
      <a href="#" style="display: inline-flex; align-items: center; gap: 10px; background: #0f172a; color: #fff; padding: 16px 32px; border-radius: 12px; text-decoration: none; font-weight: 700; font-size: 1rem;">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M3.18 23.76c.3.17.64.19.96.08l12.45-7.19-2.63-2.63-10.78 9.74zM.37 1.08C.14 1.42 0 1.88 0 2.46v19.08c0 .58.14 1.04.37 1.38l.07.07 10.69-10.69v-.25L.44 1.01l-.07.07zM20.69 10.37l-2.73-1.57-2.95 2.95 2.95 2.95 2.75-1.59c.78-.45.78-1.19-.02-1.74zM3.18.24L15.96 7.57 13.33 10.2.37 1.08C.68.59 1.14.33 1.57.33c.22 0 .44.06.64.17l.97-.26z"/></svg>
        Google Play
      </a>
    </div>
  </div>
</section>
HTML;

        $gjs_css = "* { box-sizing: border-box; margin: 0; padding: 0; } body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }";

        DB::table('pages')->updateOrInsert(
            ['slug' => 'download'],
            [
                'title'            => 'Download Aplikasi',
                'slug'             => 'download',
                'meta_title'       => 'Download Aplikasi',
                'meta_description' => 'Unduh aplikasi kami dan nikmati kemudahan layanan digital di genggaman tangan Anda.',
                'status'           => 'published',
                'use_page_builder' => 1,
                'content'          => $gjs_html,
                'gjs_components'   => $gjs_html,
                'gjs_css'          => $gjs_css,
                'gjs_styles'       => null,
                'created_at'       => now(),
                'updated_at'       => now(),
            ]
        );
    }
}
