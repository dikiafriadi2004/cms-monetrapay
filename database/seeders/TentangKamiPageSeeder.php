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

<!-- HERO (bg-white, light design matching original blade) -->
<section style="background:#fff;padding:40px 20px 64px;">
  <div style="max-width:1200px;margin:0 auto;">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:60px;align-items:center;">
      <!-- Left: text + stats -->
      <div>
        <h1 style="font-size:clamp(28px,4vw,48px);font-weight:800;color:#1e293b;line-height:1.2;margin:0 0 20px;">
          Membuat pembayaran digital terasa <span style="color:#3D9E7A;">mudah untuk semua orang.</span>
        </h1>
        <p style="font-size:clamp(15px,1.8vw,18px);color:#64748b;line-height:1.7;margin:0 0 32px;">
          MonetraPay hadir untuk mempermudah masyarakat Indonesia dalam bertransaksi digital. Kami percaya bahwa layanan keuangan digital seharusnya dapat diakses oleh siapa saja, di mana saja, kapan saja.
        </p>
        <!-- Stats row -->
        <div style="display:flex;flex-wrap:wrap;gap:24px;align-items:center;">
          <div style="text-align:center;">
            <p style="font-size:28px;font-weight:800;color:#3D9E7A;margin:0;">100K<span style="color:#f59e0b;">+</span></p>
            <p style="font-size:13px;color:#64748b;margin:4px 0 0;">Pengguna Aktif</p>
          </div>
          <div style="width:1px;height:40px;background:#e2e8f0;"></div>
          <div style="text-align:center;">
            <p style="font-size:28px;font-weight:800;color:#3D9E7A;margin:0;">500<span style="color:#f59e0b;">+</span></p>
            <p style="font-size:13px;color:#64748b;margin:4px 0 0;">Produk Digital</p>
          </div>
          <div style="width:1px;height:40px;background:#e2e8f0;"></div>
          <div style="text-align:center;">
            <p style="font-size:28px;font-weight:800;color:#3D9E7A;margin:0;">99.9<span style="color:#f59e0b;">%</span></p>
            <p style="font-size:13px;color:#64748b;margin:4px 0 0;">Uptime Server</p>
          </div>
          <div style="width:1px;height:40px;background:#e2e8f0;"></div>
          <div style="text-align:center;">
            <p style="font-size:28px;font-weight:800;color:#3D9E7A;margin:0;">2019</p>
            <p style="font-size:13px;color:#64748b;margin:4px 0 0;">Tahun Berdiri</p>
          </div>
        </div>
      </div>
      <!-- Right: logo placeholder -->
      <div style="display:flex;justify-content:center;">
        <div style="position:relative;">
          <div style="width:320px;height:320px;background:rgba(61,158,122,.08);border-radius:24px;display:flex;align-items:center;justify-content:center;">
            <span style="font-size:2.5rem;font-weight:800;color:#1e293b;">MonetraPay</span>
          </div>
          <div style="position:absolute;top:-16px;right:-16px;width:80px;height:80px;background:rgba(245,158,11,.1);border-radius:16px;"></div>
          <div style="position:absolute;bottom:-16px;left:-16px;width:56px;height:56px;background:rgba(61,158,122,.1);border-radius:12px;"></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CERITA KAMI (2-col feature cards) -->
<section style="padding:64px 20px;background:#f8fafc;">
  <div style="max-width:1200px;margin:0 auto;">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:48px;align-items:center;">
      <!-- Left: text -->
      <div>
        <span style="display:inline-block;font-size:11px;font-weight:700;color:#3D9E7A;background:rgba(61,158,122,.1);padding:6px 14px;border-radius:99px;text-transform:uppercase;letter-spacing:.1em;margin-bottom:16px;">Cerita Kami</span>
        <h2 style="font-size:clamp(22px,3.5vw,34px);font-weight:800;color:#1e293b;line-height:1.3;margin:0 0 20px;">
          Mitra pembayaran digital yang dekat dengan keseharian Anda
        </h2>
        <p style="color:#64748b;font-size:15px;line-height:1.8;margin:0 0 16px;">
          MonetraPay didirikan dengan satu tujuan: membuat transaksi digital lebih mudah, lebih cepat, dan lebih terjangkau bagi semua orang di Indonesia.
        </p>
        <p style="color:#64748b;font-size:15px;line-height:1.8;margin:0 0 16px;">
          Dari pulsa hingga tagihan listrik, dari top-up game hingga e-money, kami menyediakan ratusan produk digital dalam satu platform yang mudah digunakan.
        </p>
        <p style="color:#64748b;font-size:15px;line-height:1.8;margin:0;">
          Kami bekerja sama dengan ribuan agen di seluruh Indonesia untuk memastikan layanan kami dapat diakses oleh semua lapisan masyarakat, bahkan di daerah terpencil sekalipun.
        </p>
      </div>
      <!-- Right: 2x2 feature cards -->
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
        <div style="background:#fff;border-radius:16px;padding:24px;border:1px solid #f1f5f9;box-shadow:0 1px 4px rgba(0,0,0,.05);">
          <div style="width:48px;height:48px;background:#3D9E7A;border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:16px;">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
          </div>
          <h3 style="font-size:14px;font-weight:700;color:#1e293b;margin:0 0 8px;">Cepat &amp; Andal</h3>
          <p style="font-size:13px;color:#64748b;line-height:1.6;margin:0;">Transaksi selesai dalam hitungan detik dengan uptime 99.9%</p>
        </div>
        <div style="background:#fff;border-radius:16px;padding:24px;border:1px solid #f1f5f9;box-shadow:0 1px 4px rgba(0,0,0,.05);margin-top:24px;">
          <div style="width:48px;height:48px;background:#f59e0b;border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:16px;">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
          </div>
          <h3 style="font-size:14px;font-weight:700;color:#1e293b;margin:0 0 8px;">Aman &amp; Terpercaya</h3>
          <p style="font-size:13px;color:#64748b;line-height:1.6;margin:0;">Sistem keamanan berlapis dan dipantau 24/7</p>
        </div>
        <div style="background:#fff;border-radius:16px;padding:24px;border:1px solid #f1f5f9;box-shadow:0 1px 4px rgba(0,0,0,.05);">
          <div style="width:48px;height:48px;background:#7c3aed;border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:16px;">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
          </div>
          <h3 style="font-size:14px;font-weight:700;color:#1e293b;margin:0 0 8px;">Komunitas Agen</h3>
          <p style="font-size:13px;color:#64748b;line-height:1.6;margin:0;">Jaringan ribuan agen aktif di seluruh Indonesia</p>
        </div>
        <div style="background:#fff;border-radius:16px;padding:24px;border:1px solid #f1f5f9;box-shadow:0 1px 4px rgba(0,0,0,.05);margin-top:24px;">
          <div style="width:48px;height:48px;background:#2563eb;border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:16px;">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          </div>
          <h3 style="font-size:14px;font-weight:700;color:#1e293b;margin:0 0 8px;">Support 24/7</h3>
          <p style="font-size:13px;color:#64748b;line-height:1.6;margin:0;">Tim dukungan siap membantu kapan pun dibutuhkan</p>
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
            Menjadi platform transaksi digital terdepan yang menghubungkan jutaan masyarakat Indonesia dengan layanan keuangan digital yang mudah, cepat, dan terjangkau.
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
            <p style="font-size:14px;color:#64748b;line-height:1.6;margin:0;">Menyediakan akses layanan digital yang mudah dan terjangkau bagi semua kalangan.</p>
          </li>
          <li style="display:flex;align-items:flex-start;gap:10px;margin-bottom:14px;">
            <div style="width:20px;height:20px;border-radius:50%;background:rgba(61,158,122,.15);display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:2px;">
              <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#3D9E7A" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg>
            </div>
            <p style="font-size:14px;color:#64748b;line-height:1.6;margin:0;">Membangun ekosistem agen yang kuat dan merata di seluruh Indonesia.</p>
          </li>
          <li style="display:flex;align-items:flex-start;gap:10px;margin-bottom:14px;">
            <div style="width:20px;height:20px;border-radius:50%;background:rgba(61,158,122,.15);display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:2px;">
              <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#3D9E7A" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg>
            </div>
            <p style="font-size:14px;color:#64748b;line-height:1.6;margin:0;">Berinovasi terus-menerus untuk menghadirkan produk digital terbaik.</p>
          </li>
          <li style="display:flex;align-items:flex-start;gap:10px;">
            <div style="width:20px;height:20px;border-radius:50%;background:rgba(61,158,122,.15);display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:2px;">
              <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#3D9E7A" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg>
            </div>
            <p style="font-size:14px;color:#64748b;line-height:1.6;margin:0;">Menjaga kepercayaan pengguna dengan sistem yang aman dan transparan.</p>
          </li>
        </ul>
      </div>
    </div>
  </div>
</section>

<!-- NILAI-NILAI (4 cards) -->
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
        <p style="font-size:13px;color:#64748b;line-height:1.6;margin:0;">Kami terus belajar, berinovasi, dan berkembang untuk memberikan layanan terbaik bagi Indonesia.</p>
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
          Dari sebuah ide sederhana di tahun 2019, MonetraPay telah berkembang menjadi platform transaksi digital yang dipercaya oleh ratusan ribu pengguna di seluruh Indonesia.
        </p>
      </div>
      <!-- Right: timeline items -->
      <div>
        <!-- 2019 -->
        <div style="display:flex;gap:16px;margin-bottom:0;">
          <div style="display:flex;flex-direction:column;align-items:center;">
            <div style="width:40px;height:40px;background:#3D9E7A;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:12px;font-weight:700;flex-shrink:0;">19</div>
            <div style="width:2px;height:32px;background:#e2e8f0;margin-top:4px;"></div>
          </div>
          <div style="padding-bottom:32px;">
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
              <span style="font-size:11px;font-weight:700;color:#94a3b8;">2019</span>
              <span style="font-size:14px;font-weight:700;color:#1e293b;">Didirikan</span>
            </div>
            <p style="font-size:13px;color:#64748b;line-height:1.6;margin:0;">MonetraPay lahir dengan visi menyederhanakan transaksi digital di Indonesia.</p>
          </div>
        </div>
        <!-- 2020 -->
        <div style="display:flex;gap:16px;margin-bottom:0;">
          <div style="display:flex;flex-direction:column;align-items:center;">
            <div style="width:40px;height:40px;background:#f59e0b;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:12px;font-weight:700;flex-shrink:0;">20</div>
            <div style="width:2px;height:32px;background:#e2e8f0;margin-top:4px;"></div>
          </div>
          <div style="padding-bottom:32px;">
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
              <span style="font-size:11px;font-weight:700;color:#94a3b8;">2020</span>
              <span style="font-size:14px;font-weight:700;color:#1e293b;">Ekspansi Produk</span>
            </div>
            <p style="font-size:13px;color:#64748b;line-height:1.6;margin:0;">Menambahkan layanan PPOB dan pembayaran tagihan untuk memperluas jangkauan.</p>
          </div>
        </div>
        <!-- 2021 -->
        <div style="display:flex;gap:16px;margin-bottom:0;">
          <div style="display:flex;flex-direction:column;align-items:center;">
            <div style="width:40px;height:40px;background:#7c3aed;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:12px;font-weight:700;flex-shrink:0;">21</div>
            <div style="width:2px;height:32px;background:#e2e8f0;margin-top:4px;"></div>
          </div>
          <div style="padding-bottom:32px;">
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
              <span style="font-size:11px;font-weight:700;color:#94a3b8;">2021</span>
              <span style="font-size:14px;font-weight:700;color:#1e293b;">Jaringan Agen</span>
            </div>
            <p style="font-size:13px;color:#64748b;line-height:1.6;margin:0;">Membangun jaringan agen pertama dengan lebih dari 1.000 mitra aktif.</p>
          </div>
        </div>
        <!-- 2022 -->
        <div style="display:flex;gap:16px;margin-bottom:0;">
          <div style="display:flex;flex-direction:column;align-items:center;">
            <div style="width:40px;height:40px;background:#2563eb;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:12px;font-weight:700;flex-shrink:0;">22</div>
            <div style="width:2px;height:32px;background:#e2e8f0;margin-top:4px;"></div>
          </div>
          <div style="padding-bottom:32px;">
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
              <span style="font-size:11px;font-weight:700;color:#94a3b8;">2022</span>
              <span style="font-size:14px;font-weight:700;color:#1e293b;">Perizinan Resmi</span>
            </div>
            <p style="font-size:13px;color:#64748b;line-height:1.6;margin:0;">Mendapatkan izin resmi dari Bank Indonesia sebagai penyelenggara PPOB.</p>
          </div>
        </div>
        <!-- 2024 -->
        <div style="display:flex;gap:16px;">
          <div style="display:flex;flex-direction:column;align-items:center;">
            <div style="width:40px;height:40px;background:#3D9E7A;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:12px;font-weight:700;flex-shrink:0;">24</div>
          </div>
          <div>
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
              <span style="font-size:11px;font-weight:700;color:#94a3b8;">2024</span>
              <span style="font-size:14px;font-weight:700;color:#1e293b;">Platform Digital</span>
            </div>
            <p style="font-size:13px;color:#64748b;line-height:1.6;margin:0;">Meluncurkan platform digital baru dengan 500+ produk dan 100K+ pengguna aktif.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- LEGALITAS -->
<section style="padding:64px 20px;background:#f8fafc;">
  <div style="max-width:1200px;margin:0 auto;">
    <div style="text-align:center;margin-bottom:48px;">
      <span style="display:inline-block;font-size:11px;font-weight:700;color:#3D9E7A;background:rgba(61,158,122,.1);padding:6px 14px;border-radius:99px;text-transform:uppercase;letter-spacing:.1em;margin-bottom:16px;">Legalitas</span>
      <h2 style="font-size:clamp(22px,3.5vw,34px);font-weight:800;color:#1e293b;margin:0 0 12px;">Resmi dan terdaftar</h2>
      <p style="color:#64748b;font-size:15px;max-width:480px;margin:0 auto;">Kami beroperasi dengan izin resmi dan diawasi oleh otoritas terpercaya di Indonesia.</p>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:20px;">
      <div style="background:#fff;border-radius:16px;padding:32px 24px;border:1px solid #f1f5f9;box-shadow:0 1px 4px rgba(0,0,0,.05);text-align:center;">
        <div style="font-size:36px;margin-bottom:12px;">🏛️</div>
        <p style="font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;font-weight:600;margin:0 0 6px;">Terdaftar di</p>
        <p style="font-size:15px;font-weight:800;color:#1e293b;margin:0;">PT Monetra Payment Indonesia</p>
        <p style="font-size:12px;color:#3D9E7A;margin:4px 0 0;font-weight:600;">Badan Hukum Resmi</p>
      </div>
      <div style="background:#fff;border-radius:16px;padding:32px 24px;border:1px solid #f1f5f9;box-shadow:0 1px 4px rgba(0,0,0,.05);text-align:center;">
        <div style="font-size:36px;margin-bottom:12px;">🏦</div>
        <p style="font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;font-weight:600;margin:0 0 6px;">Diawasi oleh</p>
        <p style="font-size:15px;font-weight:800;color:#1e293b;margin:0;">OJK &amp; Bank Indonesia</p>
        <p style="font-size:12px;color:#3D9E7A;margin:4px 0 0;font-weight:600;">Otoritas Jasa Keuangan</p>
      </div>
      <div style="background:#fff;border-radius:16px;padding:32px 24px;border:1px solid #f1f5f9;box-shadow:0 1px 4px rgba(0,0,0,.05);text-align:center;">
        <div style="font-size:36px;margin-bottom:12px;">✅</div>
        <p style="font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;font-weight:600;margin:0 0 6px;">Didukung oleh</p>
        <p style="font-size:15px;font-weight:800;color:#1e293b;margin:0;">Telkom Indonesia</p>
        <p style="font-size:12px;color:#3D9E7A;margin:4px 0 0;font-weight:600;">Strategic Partner</p>
      </div>
    </div>
  </div>
</section>

<!-- TIM (4 members) -->
<section style="padding:64px 20px;background:#fff;">
  <div style="max-width:1200px;margin:0 auto;">
    <div style="text-align:center;margin-bottom:48px;">
      <span style="display:inline-block;font-size:11px;font-weight:700;color:#3D9E7A;background:rgba(61,158,122,.1);padding:6px 14px;border-radius:99px;text-transform:uppercase;letter-spacing:.1em;margin-bottom:16px;">Tim Kami</span>
      <h2 style="font-size:clamp(22px,3.5vw,34px);font-weight:800;color:#1e293b;margin:0;">Orang-orang di balik MonetraPay</h2>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:24px;">
      <div style="text-align:center;">
        <div style="width:80px;height:80px;background:#3D9E7A;border-radius:16px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:24px;font-weight:800;margin:0 auto 12px;box-shadow:0 4px 12px rgba(61,158,122,.3);">A</div>
        <h3 style="font-size:14px;font-weight:700;color:#1e293b;margin:0;">Ahmad Fauzi</h3>
        <p style="font-size:12px;color:#64748b;margin:4px 0 0;">CEO &amp; Co-Founder</p>
      </div>
      <div style="text-align:center;">
        <div style="width:80px;height:80px;background:#f59e0b;border-radius:16px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:24px;font-weight:800;margin:0 auto 12px;box-shadow:0 4px 12px rgba(245,158,11,.3);">B</div>
        <h3 style="font-size:14px;font-weight:700;color:#1e293b;margin:0;">Budi Santoso</h3>
        <p style="font-size:12px;color:#64748b;margin:4px 0 0;">CTO &amp; Co-Founder</p>
      </div>
      <div style="text-align:center;">
        <div style="width:80px;height:80px;background:#7c3aed;border-radius:16px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:24px;font-weight:800;margin:0 auto 12px;box-shadow:0 4px 12px rgba(124,58,237,.3);">C</div>
        <h3 style="font-size:14px;font-weight:700;color:#1e293b;margin:0;">Citra Dewi</h3>
        <p style="font-size:12px;color:#64748b;margin:4px 0 0;">Head of Product</p>
      </div>
      <div style="text-align:center;">
        <div style="width:80px;height:80px;background:#2563eb;border-radius:16px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:24px;font-weight:800;margin:0 auto 12px;box-shadow:0 4px 12px rgba(37,99,235,.3);">D</div>
        <h3 style="font-size:14px;font-weight:700;color:#1e293b;margin:0;">Dian Pratama</h3>
        <p style="font-size:12px;color:#64748b;margin:4px 0 0;">Head of Operations</p>
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
      Tim kami siap membantu Anda. Hubungi kami sekarang dan mulai perjalanan digital Anda bersama MonetraPay.
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
