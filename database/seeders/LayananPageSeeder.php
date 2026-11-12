<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class LayananPageSeeder extends Seeder
{
    public function run(): void
    {
        $css = "@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap'); * { box-sizing: border-box; } a { transition: opacity .2s; } a:hover { opacity: .85; }";

        $html = $this->layananHtml();

        $page = Page::firstOrCreate(
            ['slug' => 'layanan'],
            [
                'title'    => 'Layanan',
                'status'   => 'published',
                'template' => 'default',
            ]
        );

        $page->update([
            'use_page_builder' => true,
            'template'         => 'default',
            'content'          => $html,
            'gjs_components'   => $html,
            'gjs_css'          => $css,
            'gjs_styles'       => null,
        ]);

        $this->command->info('✅ LayananPageSeeder: halaman "layanan" diperbarui ke GrapesJS page builder.');
    }

    private function layananHtml(): string
    {
        return <<<'HTML'
<div style="font-family:'Plus Jakarta Sans',-apple-system,BlinkMacSystemFont,sans-serif;color:#334155;margin:0;padding:0;">

<!-- HERO SECTION -->
<section style="background:linear-gradient(135deg,#1e293b 0%,#0f172a 100%);padding:80px 20px;position:relative;overflow:hidden;">
  <div style="position:absolute;top:0;left:0;right:0;bottom:0;background-image:radial-gradient(circle,rgba(255,255,255,.05) 1px,transparent 1px);background-size:24px 24px;"></div>
  <div style="max-width:1100px;margin:0 auto;position:relative;text-align:center;">
    <h1 style="color:#fff;font-size:clamp(28px,5vw,52px);font-weight:800;line-height:1.2;margin:0 0 20px;">Layanan Kami</h1>
    <p style="color:#94a3b8;font-size:clamp(15px,2vw,18px);max-width:600px;margin:0 auto 32px;">Solusi digital terpercaya untuk kebutuhan bisnis Anda</p>
    <a href="/kontak" style="display:inline-block;background:#3D9E7A;color:#fff;padding:14px 32px;border-radius:12px;font-size:16px;font-weight:700;text-decoration:none;">Hubungi Kami</a>
  </div>
</section>

<!-- LAYANAN GRID -->
<section style="padding:80px 20px;background:#fff;">
  <div style="max-width:1100px;margin:0 auto;">
    <div style="text-align:center;margin-bottom:48px;">
      <h2 style="font-size:clamp(22px,4vw,36px);font-weight:800;color:#1e293b;margin:0 0 12px;">Apa yang Kami Tawarkan</h2>
      <p style="color:#64748b;font-size:16px;max-width:520px;margin:0 auto;">Layanan lengkap yang siap mendukung pertumbuhan bisnis Anda</p>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:24px;">

      <!-- Layanan 1 -->
      <div style="background:#fff;border:1.5px solid #e2e8f0;border-radius:16px;padding:28px 24px;">
        <div style="font-size:2.5rem;margin-bottom:16px;">🌟</div>
        <h3 style="font-size:17px;font-weight:700;color:#1e293b;margin:0 0 8px;">Layanan Unggulan Kami</h3>
        <p style="color:#64748b;font-size:14px;line-height:1.6;margin:0;">Produk dan layanan terbaik yang kami tawarkan untuk mendukung bisnis Anda</p>
      </div>

      <!-- Layanan 2 -->
      <div style="background:#fff;border:1.5px solid #e2e8f0;border-radius:16px;padding:28px 24px;">
        <div style="font-size:2.5rem;margin-bottom:16px;">💻</div>
        <h3 style="font-size:17px;font-weight:700;color:#1e293b;margin:0 0 8px;">Produk Digital</h3>
        <p style="color:#64748b;font-size:14px;line-height:1.6;margin:0;">Berbagai produk digital berkualitas tinggi yang siap memenuhi kebutuhan Anda</p>
      </div>

      <!-- Layanan 3 -->
      <div style="background:#fff;border:1.5px solid #e2e8f0;border-radius:16px;padding:28px 24px;">
        <div style="font-size:2.5rem;margin-bottom:16px;">🏢</div>
        <h3 style="font-size:17px;font-weight:700;color:#1e293b;margin:0 0 8px;">Solusi Bisnis</h3>
        <p style="color:#64748b;font-size:14px;line-height:1.6;margin:0;">Solusi komprehensif untuk membantu bisnis Anda tumbuh dan berkembang</p>
      </div>

      <!-- Layanan 4 -->
      <div style="background:#fff;border:1.5px solid #e2e8f0;border-radius:16px;padding:28px 24px;">
        <div style="font-size:2.5rem;margin-bottom:16px;">🛡️</div>
        <h3 style="font-size:17px;font-weight:700;color:#1e293b;margin:0 0 8px;">Dukungan 24/7</h3>
        <p style="color:#64748b;font-size:14px;line-height:1.6;margin:0;">Tim support kami siap membantu Anda kapan saja dan di mana saja</p>
      </div>

      <!-- Layanan 5 -->
      <div style="background:#fff;border:1.5px solid #e2e8f0;border-radius:16px;padding:28px 24px;">
        <div style="font-size:2.5rem;margin-bottom:16px;">⭐</div>
        <h3 style="font-size:17px;font-weight:700;color:#1e293b;margin:0 0 8px;">Layanan Profesional</h3>
        <p style="color:#64748b;font-size:14px;line-height:1.6;margin:0;">Standar layanan profesional dengan kualitas terjamin</p>
      </div>

      <!-- Layanan 6 -->
      <div style="background:#fff;border:1.5px solid #e2e8f0;border-radius:16px;padding:28px 24px;">
        <div style="font-size:2.5rem;margin-bottom:16px;">📱</div>
        <h3 style="font-size:17px;font-weight:700;color:#1e293b;margin:0 0 8px;">Kemudahan Akses</h3>
        <p style="color:#64748b;font-size:14px;line-height:1.6;margin:0;">Akses layanan kami dengan mudah dari berbagai perangkat</p>
      </div>

    </div>
  </div>
</section>

<!-- KEUNGGULAN SECTION -->
<section style="padding:80px 20px;background:#f8fafc;">
  <div style="max-width:1100px;margin:0 auto;">
    <div style="text-align:center;margin-bottom:48px;">
      <h2 style="font-size:clamp(22px,4vw,36px);font-weight:800;color:#1e293b;margin:0 0 12px;">Mengapa Memilih Kami</h2>
      <p style="color:#64748b;font-size:16px;">Platform terpercaya untuk mendukung keberhasilan bisnis Anda</p>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:24px;">
      <div style="background:#fff;border-radius:16px;padding:32px 24px;text-align:center;box-shadow:0 1px 3px rgba(0,0,0,.06);">
        <div style="font-size:clamp(28px,4vw,42px);font-weight:800;color:#3D9E7A;margin-bottom:8px;">100%</div>
        <div style="font-size:15px;font-weight:600;color:#1e293b;margin-bottom:4px;">Kepuasan Pelanggan</div>
        <div style="font-size:13px;color:#64748b;">Prioritas utama kami</div>
      </div>
      <div style="background:#fff;border-radius:16px;padding:32px 24px;text-align:center;box-shadow:0 1px 3px rgba(0,0,0,.06);">
        <div style="font-size:clamp(28px,4vw,42px);font-weight:800;color:#3D9E7A;margin-bottom:8px;">99.9%</div>
        <div style="font-size:15px;font-weight:600;color:#1e293b;margin-bottom:4px;">Uptime</div>
        <div style="font-size:13px;color:#64748b;">Server yang selalu stabil</div>
      </div>
      <div style="background:#fff;border-radius:16px;padding:32px 24px;text-align:center;box-shadow:0 1px 3px rgba(0,0,0,.06);">
        <div style="font-size:clamp(28px,4vw,42px);font-weight:800;color:#3D9E7A;margin-bottom:8px;">24/7</div>
        <div style="font-size:15px;font-weight:600;color:#1e293b;margin-bottom:4px;">Support</div>
        <div style="font-size:13px;color:#64748b;">Siap membantu kapan saja</div>
      </div>
      <div style="background:#fff;border-radius:16px;padding:32px 24px;text-align:center;box-shadow:0 1px 3px rgba(0,0,0,.06);">
        <div style="font-size:clamp(28px,4vw,42px);font-weight:800;color:#3D9E7A;margin-bottom:8px;">🔒</div>
        <div style="font-size:15px;font-weight:600;color:#1e293b;margin-bottom:4px;">Keamanan Data</div>
        <div style="font-size:13px;color:#64748b;">Enkripsi tingkat tinggi</div>
      </div>
    </div>
  </div>
</section>

<!-- CTA SECTION -->
<section style="padding:80px 20px;background:#3D9E7A;">
  <div style="max-width:700px;margin:0 auto;text-align:center;">
    <h2 style="font-size:clamp(24px,4vw,38px);font-weight:800;color:#fff;margin:0 0 16px;">Siap Memulai?</h2>
    <p style="color:rgba(255,255,255,.85);font-size:17px;margin:0 0 32px;">Hubungi kami sekarang dan temukan solusi terbaik untuk bisnis Anda</p>
    <a href="/kontak" style="display:inline-block;background:#fff;color:#3D9E7A;padding:16px 40px;border-radius:12px;font-size:17px;font-weight:800;text-decoration:none;">Hubungi Kami</a>
  </div>
</section>

</div>
HTML;
    }
}
