<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class TentangKamiPageSeeder extends Seeder
{
    public function run(): void
    {
        $css = "@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap'); * { box-sizing: border-box; } .animate-on-scroll { opacity: 1 !important; transform: none !important; } a { transition: opacity .2s; } a:hover { opacity: .85; }";

        $html = $this->tentangKamiHtml();

        $page = Page::firstOrCreate(
            ['slug' => 'tentang-kami'],
            [
                'title'    => 'Tentang Kami',
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

        $this->command->info('✅ TentangKamiPageSeeder: halaman "tentang-kami" diperbarui ke GrapesJS page builder.');
    }

    private function tentangKamiHtml(): string
    {
        return <<<'HTML'
<div class="font-sans" style="font-family:'Plus Jakarta Sans',-apple-system,BlinkMacSystemFont,sans-serif;color:#334155;margin:0;padding:0;">

<!-- HERO -->
<section style="background:#fff;padding:40px 20px 64px;">
  <div style="max-width:1200px;margin:0 auto;">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:60px;align-items:center;">
      <!-- Left: text -->
      <div>
        <h1 style="font-size:clamp(28px,4vw,48px);font-weight:800;color:#1e293b;line-height:1.2;margin:0 0 20px;">
          Tentang Kami
        </h1>
        <p style="font-size:clamp(15px,1.8vw,18px);color:#64748b;line-height:1.7;margin:0 0 32px;">
          Platform digital terpercaya yang hadir untuk memudahkan kehidupan digital Anda. Kami adalah platform digital terpercaya yang melayani pelanggan dengan dedikasi dan profesionalisme tinggi, berkomitmen untuk menghadirkan pengalaman digital yang mudah, cepat, dan aman bagi setiap pengguna.
        </p>
        <div style="display:flex;flex-wrap:wrap;gap:24px;align-items:center;">
          <div style="text-align:center;">
            <p style="font-size:28px;font-weight:800;color:#3D9E7A;margin:0;">100K<span style="color:#f59e0b;">+</span></p>
            <p style="font-size:13px;color:#64748b;margin:4px 0 0;">Pengguna Aktif</p>
          </div>
          <div style="width:1px;height:40px;background:#e2e8f0;"></div>
          <div style="text-align:center;">
            <p style="font-size:28px;font-weight:800;color:#3D9E7A;margin:0;">99.9<span style="color:#f59e0b;">%</span></p>
            <p style="font-size:13px;color:#64748b;margin:4px 0 0;">Uptime Server</p>
          </div>
          <div style="width:1px;height:40px;background:#e2e8f0;"></div>
          <div style="text-align:center;">
            <p style="font-size:28px;font-weight:800;color:#3D9E7A;margin:0;">24/7</p>
            <p style="font-size:13px;color:#64748b;margin:4px 0 0;">Dukungan</p>
          </div>
        </div>
      </div>
      <!-- Right: logo placeholder -->
      <div style="display:flex;justify-content:center;">
        <div style="position:relative;">
          <div style="width:320px;height:320px;background:rgba(61,158,122,.08);border-radius:24px;display:flex;align-items:center;justify-content:center;">
            <span style="font-size:2rem;font-weight:800;color:#1e293b;text-align:center;padding:20px;">Platform Digital Terpercaya</span>
          </div>
          <div style="position:absolute;top:-16px;right:-16px;width:80px;height:80px;background:rgba(245,158,11,.1);border-radius:16px;"></div>
          <div style="position:absolute;bottom:-16px;left:-16px;width:56px;height:56px;background:rgba(61,158,122,.1);border-radius:12px;"></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- VISI MISI -->
<section style="padding:64px 20px;background:#fff;">
  <div style="max-width:1200px;margin:0 auto;">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:32px;">
      <!-- Visi -->
      <div style="background:#3D9E7A;border-radius:24px;padding:40px;color:#fff;position:relative;overflow:hidden;">
        <div style="position:absolute;top:-40px;right:-40px;width:160px;height:160px;background:rgba(255,255,255,.05);border-radius:50%;"></div>
        <div style="position:relative;">
          <div style="width:48px;height:48px;background:rgba(255,255,255,.2);border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:24px;">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
          </div>
          <h3 style="font-size:20px;font-weight:800;margin:0 0 16px;">Visi</h3>
          <p style="color:rgba(255,255,255,.85);font-size:15px;line-height:1.7;margin:0;">
            Menjadi platform digital terdepan yang memberikan solusi terbaik bagi setiap pengguna.
          </p>
        </div>
      </div>
      <!-- Misi -->
      <div style="background:#f8fafc;border-radius:24px;padding:40px;border:1px solid #f1f5f9;">
        <div style="width:48px;height:48px;background:rgba(245,158,11,.15);border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:24px;">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
        </div>
        <h3 style="font-size:20px;font-weight:800;color:#1e293b;margin:0 0 16px;">Misi</h3>
        <ul style="list-style:none;margin:0;padding:0;">
          <li style="display:flex;align-items:flex-start;gap:10px;margin-bottom:14px;">
            <div style="width:20px;height:20px;border-radius:50%;background:rgba(61,158,122,.15);display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:2px;">
              <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#3D9E7A" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg>
            </div>
            <p style="font-size:14px;color:#64748b;line-height:1.6;margin:0;">Menyediakan layanan digital berkualitas tinggi yang dapat diandalkan.</p>
          </li>
          <li style="display:flex;align-items:flex-start;gap:10px;margin-bottom:14px;">
            <div style="width:20px;height:20px;border-radius:50%;background:rgba(61,158,122,.15);display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:2px;">
              <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#3D9E7A" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg>
            </div>
            <p style="font-size:14px;color:#64748b;line-height:1.6;margin:0;">Memberikan pengalaman pengguna yang mudah, nyaman, dan menyenangkan.</p>
          </li>
          <li style="display:flex;align-items:flex-start;gap:10px;margin-bottom:14px;">
            <div style="width:20px;height:20px;border-radius:50%;background:rgba(61,158,122,.15);display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:2px;">
              <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#3D9E7A" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg>
            </div>
            <p style="font-size:14px;color:#64748b;line-height:1.6;margin:0;">Terus berinovasi untuk menghadirkan solusi teknologi terkini.</p>
          </li>
          <li style="display:flex;align-items:flex-start;gap:10px;">
            <div style="width:20px;height:20px;border-radius:50%;background:rgba(61,158,122,.15);display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:2px;">
              <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#3D9E7A" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg>
            </div>
            <p style="font-size:14px;color:#64748b;line-height:1.6;margin:0;">Membangun kepercayaan melalui transparansi dan integritas.</p>
          </li>
        </ul>
      </div>
    </div>
  </div>
</section>

<!-- NILAI-NILAI -->
<section style="padding:64px 20px;background:#f8fafc;">
  <div style="max-width:1200px;margin:0 auto;">
    <div style="text-align:center;margin-bottom:48px;">
      <span style="display:inline-block;font-size:11px;font-weight:700;color:#3D9E7A;background:rgba(61,158,122,.1);padding:6px 14px;border-radius:99px;text-transform:uppercase;letter-spacing:.1em;margin-bottom:16px;">Nilai Kami</span>
      <h2 style="font-size:clamp(22px,3.5vw,34px);font-weight:800;color:#1e293b;margin:0;">Prinsip yang kami pegang</h2>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:20px;">
      <div style="background:#fff;border-radius:16px;padding:28px 24px;border:1px solid #f1f5f9;box-shadow:0 1px 4px rgba(0,0,0,.05);">
        <div style="font-size:28px;margin-bottom:12px;">🤝</div>
        <h3 style="font-size:15px;font-weight:700;color:#1e293b;margin:0 0 8px;">Transparansi</h3>
        <p style="font-size:13px;color:#64748b;line-height:1.6;margin:0;">Kami percaya pada keterbukaan informasi dan komunikasi yang jujur kepada seluruh pemangku kepentingan.</p>
      </div>
      <div style="background:#fff;border-radius:16px;padding:28px 24px;border:1px solid #f1f5f9;box-shadow:0 1px 4px rgba(0,0,0,.05);">
        <div style="font-size:28px;margin-bottom:12px;">🛡️</div>
        <h3 style="font-size:15px;font-weight:700;color:#1e293b;margin:0 0 8px;">Dapat Dipercaya</h3>
        <p style="font-size:13px;color:#64748b;line-height:1.6;margin:0;">Keamanan dan kepercayaan pengguna adalah prioritas utama dalam setiap keputusan yang kami buat.</p>
      </div>
      <div style="background:#fff;border-radius:16px;padding:28px 24px;border:1px solid #f1f5f9;box-shadow:0 1px 4px rgba(0,0,0,.05);">
        <div style="font-size:28px;margin-bottom:12px;">💡</div>
        <h3 style="font-size:15px;font-weight:700;color:#1e293b;margin:0 0 8px;">Fokus Pengguna</h3>
        <p style="font-size:13px;color:#64748b;line-height:1.6;margin:0;">Setiap fitur dan layanan kami dirancang dengan mengutamakan kemudahan dan kebutuhan pengguna.</p>
      </div>
      <div style="background:#fff;border-radius:16px;padding:28px 24px;border:1px solid #f1f5f9;box-shadow:0 1px 4px rgba(0,0,0,.05);">
        <div style="font-size:28px;margin-bottom:12px;">🌱</div>
        <h3 style="font-size:15px;font-weight:700;color:#1e293b;margin:0 0 8px;">Terus Berkembang</h3>
        <p style="font-size:13px;color:#64748b;line-height:1.6;margin:0;">Kami terus belajar, berinovasi, dan berkembang untuk memberikan layanan terbaik.</p>
      </div>
    </div>
  </div>
</section>

<!-- PERJALANAN (timeline) -->
<section style="padding:64px 20px;background:#fff;">
  <div style="max-width:1200px;margin:0 auto;">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:48px;align-items:flex-start;">
      <!-- Left: intro -->
      <div>
        <span style="display:inline-block;font-size:11px;font-weight:700;color:#3D9E7A;background:rgba(61,158,122,.1);padding:6px 14px;border-radius:99px;text-transform:uppercase;letter-spacing:.1em;margin-bottom:16px;">Perjalanan Kami</span>
        <h2 style="font-size:clamp(22px,3.5vw,34px);font-weight:800;color:#1e293b;line-height:1.3;margin:0 0 16px;">
          Tumbuh selangkah demi selangkah
        </h2>
        <p style="color:#64748b;font-size:15px;line-height:1.7;margin:0;">
          Dari sebuah ide sederhana, platform kami terus berkembang menjadi solusi digital yang dipercaya oleh ribuan pengguna di seluruh Indonesia.
        </p>
      </div>
      <!-- Right: timeline items -->
      <div>
        <!-- Tahun Pertama -->
        <div style="display:flex;gap:16px;margin-bottom:0;">
          <div style="display:flex;flex-direction:column;align-items:center;">
            <div style="width:40px;height:40px;background:#3D9E7A;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:12px;font-weight:700;flex-shrink:0;">01</div>
            <div style="width:2px;height:32px;background:#e2e8f0;margin-top:4px;"></div>
          </div>
          <div style="padding-bottom:32px;">
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
              <span style="font-size:14px;font-weight:700;color:#1e293b;">Tahun Pertama</span>
            </div>
            <p style="font-size:13px;color:#64748b;line-height:1.6;margin:0;">Peluncuran platform dan layanan perdana.</p>
          </div>
        </div>
        <!-- Pengembangan -->
        <div style="display:flex;gap:16px;margin-bottom:0;">
          <div style="display:flex;flex-direction:column;align-items:center;">
            <div style="width:40px;height:40px;background:#f59e0b;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:12px;font-weight:700;flex-shrink:0;">02</div>
            <div style="width:2px;height:32px;background:#e2e8f0;margin-top:4px;"></div>
          </div>
          <div style="padding-bottom:32px;">
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
              <span style="font-size:14px;font-weight:700;color:#1e293b;">Pengembangan</span>
            </div>
            <p style="font-size:13px;color:#64748b;line-height:1.6;margin:0;">Penambahan fitur dan peningkatan layanan.</p>
          </div>
        </div>
        <!-- Pertumbuhan -->
        <div style="display:flex;gap:16px;margin-bottom:0;">
          <div style="display:flex;flex-direction:column;align-items:center;">
            <div style="width:40px;height:40px;background:#7c3aed;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:12px;font-weight:700;flex-shrink:0;">03</div>
            <div style="width:2px;height:32px;background:#e2e8f0;margin-top:4px;"></div>
          </div>
          <div style="padding-bottom:32px;">
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
              <span style="font-size:14px;font-weight:700;color:#1e293b;">Pertumbuhan</span>
            </div>
            <p style="font-size:13px;color:#64748b;line-height:1.6;margin:0;">Peningkatan signifikan jumlah pengguna dan mitra.</p>
          </div>
        </div>
        <!-- Inovasi -->
        <div style="display:flex;gap:16px;">
          <div style="display:flex;flex-direction:column;align-items:center;">
            <div style="width:40px;height:40px;background:#2563eb;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:12px;font-weight:700;flex-shrink:0;">04</div>
          </div>
          <div>
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
              <span style="font-size:14px;font-weight:700;color:#1e293b;">Inovasi</span>
            </div>
            <p style="font-size:13px;color:#64748b;line-height:1.6;margin:0;">Implementasi teknologi terbaru untuk pengalaman lebih baik.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section style="padding:64px 20px;background:#3D9E7A;">
  <div style="max-width:800px;margin:0 auto;text-align:center;">
    <h2 style="font-size:clamp(24px,4vw,38px);font-weight:800;color:#fff;margin:0 0 16px;">
      Ingin bekerja sama atau sekadar bertanya?
    </h2>
    <p style="color:rgba(255,255,255,.8);font-size:clamp(15px,2vw,18px);margin:0 0 32px;max-width:560px;margin-left:auto;margin-right:auto;">
      Tim kami siap membantu Anda. Hubungi kami sekarang dan mulai perjalanan digital Anda bersama kami.
    </p>
    <a href="/kontak" style="display:inline-flex;align-items:center;gap:8px;background:#fff;color:#3D9E7A;padding:16px 40px;border-radius:16px;font-size:16px;font-weight:800;text-decoration:none;box-shadow:0 4px 16px rgba(0,0,0,.1);">
      Hubungi Kami
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
    </a>
  </div>
</section>

</div>
HTML;
    }
}
