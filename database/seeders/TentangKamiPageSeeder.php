<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class TentangKamiPageSeeder extends Seeder
{
    public function run(): void
    {
        $css = "@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap'); * { box-sizing: border-box; } a { transition: opacity .2s; } a:hover { opacity: .85; }";

        $html = $this->tentangKamiHtml();

        $page = Page::where('slug', 'tentang-kami')->first();

        if (! $page) {
            $this->command->warn('⚠️  Halaman dengan slug "tentang-kami" tidak ditemukan. Seeder dibatalkan.');
            return;
        }

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
<div style="font-family:'Plus Jakarta Sans',-apple-system,BlinkMacSystemFont,sans-serif;color:#334155;margin:0;padding:0;">

<!-- HERO -->
<section style="background:linear-gradient(135deg,#1e293b 0%,#0f172a 100%);padding:80px 20px;position:relative;overflow:hidden;">
  <div style="position:absolute;top:0;left:0;right:0;bottom:0;background-image:radial-gradient(circle,rgba(255,255,255,.05) 1px,transparent 1px);background-size:24px 24px;"></div>
  <div style="max-width:1100px;margin:0 auto;position:relative;text-align:center;">
    <span style="display:inline-block;background:rgba(61,158,122,.2);color:#4ade80;padding:6px 16px;border-radius:99px;font-size:13px;font-weight:600;margin-bottom:20px;">Tentang Kami</span>
    <h1 style="color:#fff;font-size:clamp(28px,5vw,52px);font-weight:800;line-height:1.2;margin:0 0 20px;">Tentang <span style="color:#4ade80;">MonetraPay</span></h1>
    <p style="color:#94a3b8;font-size:clamp(15px,2vw,18px);max-width:600px;margin:0 auto;">Solusi Pembayaran Digital Terlengkap untuk Seluruh Indonesia</p>
  </div>
</section>

<!-- STATS ROW -->
<section style="padding:48px 20px;background:#f8fafc;">
  <div style="max-width:1100px;margin:0 auto;">
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:20px;">
      <div style="background:#fff;border-radius:14px;padding:24px;text-align:center;box-shadow:0 1px 3px rgba(0,0,0,.06);">
        <div style="font-size:36px;font-weight:800;color:#3D9E7A;">500K+</div>
        <div style="font-size:14px;font-weight:600;color:#1e293b;">Agen Aktif</div>
      </div>
      <div style="background:#fff;border-radius:14px;padding:24px;text-align:center;box-shadow:0 1px 3px rgba(0,0,0,.06);">
        <div style="font-size:36px;font-weight:800;color:#3D9E7A;">100+</div>
        <div style="font-size:14px;font-weight:600;color:#1e293b;">Produk</div>
      </div>
      <div style="background:#fff;border-radius:14px;padding:24px;text-align:center;box-shadow:0 1px 3px rgba(0,0,0,.06);">
        <div style="font-size:36px;font-weight:800;color:#3D9E7A;">99.9%</div>
        <div style="font-size:14px;font-weight:600;color:#1e293b;">Uptime</div>
      </div>
      <div style="background:#fff;border-radius:14px;padding:24px;text-align:center;box-shadow:0 1px 3px rgba(0,0,0,.06);">
        <div style="font-size:36px;font-weight:800;color:#3D9E7A;">2020</div>
        <div style="font-size:14px;font-weight:600;color:#1e293b;">Berdiri Sejak</div>
      </div>
    </div>
  </div>
</section>

<!-- TENTANG KAMI PARAGRAPH -->
<section style="padding:80px 20px;background:#fff;">
  <div style="max-width:900px;margin:0 auto;">
    <div style="text-align:center;margin-bottom:48px;">
      <h2 style="font-size:clamp(22px,4vw,36px);font-weight:800;color:#1e293b;margin:0 0 24px;">Siapa Kami?</h2>
      <p style="color:#475569;font-size:16px;line-height:1.8;margin:0 0 16px;">MonetraPay adalah platform transaksi digital all-in-one yang hadir untuk memberdayakan masyarakat Indonesia melalui bisnis digital. Kami menyediakan layanan PPOB (Payment Point Online Bank), pulsa, top-up game, dan e-wallet yang dapat diakses oleh siapapun di seluruh penjuru Indonesia.</p>
      <p style="color:#475569;font-size:16px;line-height:1.8;margin:0;">Sejak berdiri pada tahun 2020, MonetraPay telah melayani lebih dari 500.000 agen aktif dan memproses jutaan transaksi setiap harinya. Dengan teknologi terdepan dan dukungan 24/7, kami berkomitmen untuk menjadi mitra bisnis digital terpercaya Anda.</p>
    </div>
  </div>
</section>

<!-- VISI MISI -->
<section style="padding:80px 20px;background:#f8fafc;">
  <div style="max-width:1100px;margin:0 auto;">
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:32px;">

      <!-- Visi -->
      <div style="background:#fff;border-radius:20px;padding:40px 32px;border-left:4px solid #3D9E7A;">
        <div style="width:48px;height:48px;background:#dcfce7;border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:20px;">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#3D9E7A" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
        </div>
        <h3 style="font-size:20px;font-weight:800;color:#1e293b;margin:0 0 16px;">Visi Kami</h3>
        <p style="color:#475569;font-size:15px;line-height:1.7;margin:0;">Menjadi platform transaksi digital terdepan yang memberdayakan masyarakat Indonesia menuju kemandirian finansial dan inklusi keuangan yang merata.</p>
      </div>

      <!-- Misi -->
      <div style="background:#fff;border-radius:20px;padding:40px 32px;border-left:4px solid #0ea5e9;">
        <div style="width:48px;height:48px;background:#dbeafe;border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:20px;">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#0ea5e9" stroke-width="2"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
        </div>
        <h3 style="font-size:20px;font-weight:800;color:#1e293b;margin:0 0 16px;">Misi Kami</h3>
        <ul style="color:#475569;font-size:15px;line-height:1.8;margin:0;padding-left:20px;">
          <li style="margin-bottom:8px;">Menyediakan layanan PPOB terlengkap dengan harga terjangkau</li>
          <li style="margin-bottom:8px;">Memberikan margin kompetitif yang menguntungkan para agen</li>
          <li>Mendorong inklusi keuangan di seluruh pelosok Indonesia</li>
        </ul>
      </div>
    </div>
  </div>
</section>

<!-- NILAI PERUSAHAAN -->
<section style="padding:80px 20px;background:#fff;">
  <div style="max-width:1100px;margin:0 auto;">
    <div style="text-align:center;margin-bottom:48px;">
      <h2 style="font-size:clamp(22px,4vw,36px);font-weight:800;color:#1e293b;margin:0 0 12px;">Nilai Perusahaan</h2>
      <p style="color:#64748b;font-size:16px;">Empat pilar yang menjadi fondasi MonetraPay</p>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(230px,1fr));gap:24px;">
      <div style="background:#f8fafc;border-radius:16px;padding:32px 24px;text-align:center;">
        <div style="font-size:32px;margin-bottom:12px;">🤝</div>
        <h4 style="font-size:17px;font-weight:700;color:#1e293b;margin:0 0 8px;">Kepercayaan</h4>
        <p style="color:#64748b;font-size:14px;line-height:1.6;margin:0;">Transparansi dan kejujuran dalam setiap transaksi dan hubungan bisnis</p>
      </div>
      <div style="background:#f8fafc;border-radius:16px;padding:32px 24px;text-align:center;">
        <div style="font-size:32px;margin-bottom:12px;">💡</div>
        <h4 style="font-size:17px;font-weight:700;color:#1e293b;margin:0 0 8px;">Inovasi</h4>
        <p style="color:#64748b;font-size:14px;line-height:1.6;margin:0;">Terus berinovasi menghadirkan solusi digital terbaik untuk kebutuhan agen</p>
      </div>
      <div style="background:#f8fafc;border-radius:16px;padding:32px 24px;text-align:center;">
        <div style="font-size:32px;margin-bottom:12px;">⚡</div>
        <h4 style="font-size:17px;font-weight:700;color:#1e293b;margin:0 0 8px;">Kecepatan</h4>
        <p style="color:#64748b;font-size:14px;line-height:1.6;margin:0;">Proses transaksi yang cepat dan responsif untuk kepuasan agen dan pelanggan</p>
      </div>
      <div style="background:#f8fafc;border-radius:16px;padding:32px 24px;text-align:center;">
        <div style="font-size:32px;margin-bottom:12px;">📊</div>
        <h4 style="font-size:17px;font-weight:700;color:#1e293b;margin:0 0 8px;">Transparansi</h4>
        <p style="color:#64748b;font-size:14px;line-height:1.6;margin:0;">Laporan real-time dan sistem yang terbuka untuk semua mitra agen kami</p>
      </div>
    </div>
  </div>
</section>

<!-- TIMELINE -->
<section style="padding:80px 20px;background:#f8fafc;">
  <div style="max-width:800px;margin:0 auto;">
    <div style="text-align:center;margin-bottom:48px;">
      <h2 style="font-size:clamp(22px,4vw,36px);font-weight:800;color:#1e293b;margin:0 0 12px;">Perjalanan Kami</h2>
      <p style="color:#64748b;font-size:16px;">Dari startup kecil menjadi platform dengan 500K+ agen</p>
    </div>
    <div style="position:relative;">
      <div style="position:absolute;left:24px;top:0;bottom:0;width:2px;background:#e2e8f0;"></div>

      <!-- Timeline items -->
      <div style="display:flex;align-items:flex-start;gap:20px;margin-bottom:32px;position:relative;">
        <div style="width:48px;height:48px;background:#3D9E7A;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;z-index:1;">
          <span style="color:#fff;font-size:13px;font-weight:700;">2020</span>
        </div>
        <div style="background:#fff;border-radius:14px;padding:20px 24px;flex:1;box-shadow:0 1px 3px rgba(0,0,0,.06);">
          <h4 style="font-size:16px;font-weight:700;color:#1e293b;margin:0 0 6px;">🚀 MonetraPay Berdiri</h4>
          <p style="color:#64748b;font-size:14px;margin:0;">Platform diluncurkan dengan layanan pulsa dan PPOB dasar, melayani agen pertama di Jakarta.</p>
        </div>
      </div>

      <div style="display:flex;align-items:flex-start;gap:20px;margin-bottom:32px;position:relative;">
        <div style="width:48px;height:48px;background:#0ea5e9;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;z-index:1;">
          <span style="color:#fff;font-size:13px;font-weight:700;">2021</span>
        </div>
        <div style="background:#fff;border-radius:14px;padding:20px 24px;flex:1;box-shadow:0 1px 3px rgba(0,0,0,.06);">
          <h4 style="font-size:16px;font-weight:700;color:#1e293b;margin:0 0 6px;">📈 100.000 Agen Aktif</h4>
          <p style="color:#64748b;font-size:14px;margin:0;">Pertumbuhan pesat dengan penambahan fitur top-up game dan e-wallet. Ekspansi ke Jawa dan Bali.</p>
        </div>
      </div>

      <div style="display:flex;align-items:flex-start;gap:20px;margin-bottom:32px;position:relative;">
        <div style="width:48px;height:48px;background:#7c3aed;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;z-index:1;">
          <span style="color:#fff;font-size:13px;font-weight:700;">2022</span>
        </div>
        <div style="background:#fff;border-radius:14px;padding:20px 24px;flex:1;box-shadow:0 1px 3px rgba(0,0,0,.06);">
          <h4 style="font-size:16px;font-weight:700;color:#1e293b;margin:0 0 6px;">🌏 Ekspansi Nasional</h4>
          <p style="color:#64748b;font-size:14px;margin:0;">Menjangkau seluruh wilayah Indonesia termasuk Kalimantan, Sulawesi, dan Papua. Peluncuran fitur transfer bank.</p>
        </div>
      </div>

      <div style="display:flex;align-items:flex-start;gap:20px;margin-bottom:32px;position:relative;">
        <div style="width:48px;height:48px;background:#ea580c;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;z-index:1;">
          <span style="color:#fff;font-size:13px;font-weight:700;">2023</span>
        </div>
        <div style="background:#fff;border-radius:14px;padding:20px 24px;flex:1;box-shadow:0 1px 3px rgba(0,0,0,.06);">
          <h4 style="font-size:16px;font-weight:700;color:#1e293b;margin:0 0 6px;">🏆 500.000 Agen Aktif</h4>
          <p style="color:#64748b;font-size:14px;margin:0;">Milestone bersejarah: 500K agen aktif! Penghargaan fintech terbaik dari Kemenkominfo.</p>
        </div>
      </div>

      <div style="display:flex;align-items:flex-start;gap:20px;position:relative;">
        <div style="width:48px;height:48px;background:#1e293b;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;z-index:1;">
          <span style="color:#fff;font-size:13px;font-weight:700;">2024</span>
        </div>
        <div style="background:#fff;border-radius:14px;padding:20px 24px;flex:1;box-shadow:0 1px 3px rgba(0,0,0,.06);">
          <h4 style="font-size:16px;font-weight:700;color:#1e293b;margin:0 0 6px;">🔥 Platform Generasi Baru</h4>
          <p style="color:#64748b;font-size:14px;margin:0;">Peluncuran platform baru dengan antarmuka modern, API yang lebih cepat, dan fitur manajemen agen yang canggih.</p>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- LEGALITAS -->
<section style="padding:80px 20px;background:#fff;">
  <div style="max-width:800px;margin:0 auto;text-align:center;">
    <h2 style="font-size:clamp(22px,4vw,36px);font-weight:800;color:#1e293b;margin:0 0 12px;">Legalitas &amp; Sertifikasi</h2>
    <p style="color:#64748b;font-size:16px;margin:0 0 40px;">MonetraPay beroperasi secara legal dan telah memenuhi semua persyaratan regulasi</p>
    <div style="display:flex;flex-wrap:wrap;gap:20px;justify-content:center;">
      <div style="background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:14px;padding:20px 28px;min-width:180px;">
        <div style="color:#3D9E7A;font-size:24px;margin-bottom:8px;">✅</div>
        <div style="font-size:14px;font-weight:700;color:#166534;">Terdaftar OJK</div>
        <div style="font-size:12px;color:#64748b;margin-top:4px;">Otoritas Jasa Keuangan</div>
      </div>
      <div style="background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:14px;padding:20px 28px;min-width:180px;">
        <div style="color:#3D9E7A;font-size:24px;margin-bottom:8px;">✅</div>
        <div style="font-size:14px;font-weight:700;color:#166534;">Izin Kemkominfo</div>
        <div style="font-size:12px;color:#64748b;margin-top:4px;">Kementerian Komunikasi</div>
      </div>
      <div style="background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:14px;padding:20px 28px;min-width:180px;">
        <div style="color:#3D9E7A;font-size:24px;margin-bottom:8px;">✅</div>
        <div style="font-size:14px;font-weight:700;color:#166534;">ISO 27001 Certified</div>
        <div style="font-size:12px;color:#64748b;margin-top:4px;">Keamanan Informasi</div>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section style="padding:80px 20px;background:#3D9E7A;">
  <div style="max-width:700px;margin:0 auto;text-align:center;">
    <h2 style="font-size:clamp(24px,4vw,38px);font-weight:800;color:#fff;margin:0 0 16px;">Bergabung Bersama 500K+ Agen MonetraPay</h2>
    <p style="color:rgba(255,255,255,.85);font-size:17px;margin:0 0 32px;">Mulai perjalanan bisnis digital Anda bersama platform terpercaya</p>
    <a href="/kontak" style="display:inline-block;background:#fff;color:#3D9E7A;padding:16px 40px;border-radius:12px;font-size:17px;font-weight:800;text-decoration:none;">Daftar Sekarang &rarr;</a>
  </div>
</section>

</div>
HTML;
    }
}
