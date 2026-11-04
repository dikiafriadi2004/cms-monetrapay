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

        $page = Page::where('slug', 'layanan')->first();

        if (! $page) {
            $this->command->warn('⚠️  Halaman dengan slug "layanan" tidak ditemukan. Seeder dibatalkan.');
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
    <span style="display:inline-block;background:rgba(61,158,122,.2);color:#4ade80;padding:6px 16px;border-radius:99px;font-size:13px;font-weight:600;margin-bottom:20px;">Platform PPOB Terlengkap</span>
    <h1 style="color:#fff;font-size:clamp(28px,5vw,52px);font-weight:800;line-height:1.2;margin:0 0 20px;">Layanan Lengkap untuk<br><span style="color:#4ade80;">Agen Digital</span></h1>
    <p style="color:#94a3b8;font-size:clamp(15px,2vw,18px);max-width:600px;margin:0 auto 32px;">Dari pulsa hingga tagihan, semua tersedia dalam satu platform yang mudah digunakan</p>
    <a href="/kontak" style="display:inline-block;background:#3D9E7A;color:#fff;padding:14px 32px;border-radius:12px;font-size:16px;font-weight:700;text-decoration:none;">Daftar Jadi Agen Sekarang</a>
  </div>
</section>

<!-- PRODUCTS GRID -->
<section style="padding:80px 20px;background:#fff;">
  <div style="max-width:1100px;margin:0 auto;">
    <div style="text-align:center;margin-bottom:48px;">
      <h2 style="font-size:clamp(22px,4vw,36px);font-weight:800;color:#1e293b;margin:0 0 12px;">8 Kategori Layanan Lengkap</h2>
      <p style="color:#64748b;font-size:16px;max-width:520px;margin:0 auto;">Semua kebutuhan transaksi digital tersedia 24 jam sehari, 7 hari seminggu</p>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:24px;">

      <!-- Kartu 1: Pulsa -->
      <div style="background:#fff;border:1.5px solid #e2e8f0;border-radius:16px;padding:28px 24px;transition:all .2s;">
        <div style="width:52px;height:52px;background:#dcfce7;border-radius:14px;display:flex;align-items:center;justify-content:center;margin-bottom:16px;">
          <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#3D9E7A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
        </div>
        <div style="display:inline-block;background:#dcfce7;color:#166534;font-size:11px;font-weight:600;padding:3px 10px;border-radius:99px;margin-bottom:10px;">Tersedia 24/7</div>
        <h3 style="font-size:17px;font-weight:700;color:#1e293b;margin:0 0 8px;">Pulsa &amp; Paket Data</h3>
        <p style="color:#64748b;font-size:14px;line-height:1.6;margin:0;">Semua operator tersedia: Telkomsel, Indosat, XL, Tri, dan Smartfren. Margin kompetitif untuk setiap agen.</p>
      </div>

      <!-- Kartu 2: PLN -->
      <div style="background:#fff;border:1.5px solid #e2e8f0;border-radius:16px;padding:28px 24px;">
        <div style="width:52px;height:52px;background:#fef9c3;border-radius:14px;display:flex;align-items:center;justify-content:center;margin-bottom:16px;">
          <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#ca8a04" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
        </div>
        <div style="display:inline-block;background:#dcfce7;color:#166534;font-size:11px;font-weight:600;padding:3px 10px;border-radius:99px;margin-bottom:10px;">Tersedia 24/7</div>
        <h3 style="font-size:17px;font-weight:700;color:#1e293b;margin:0 0 8px;">Token &amp; Tagihan Listrik PLN</h3>
        <p style="color:#64748b;font-size:14px;line-height:1.6;margin:0;">Prabayar dan pascabayar PLN tersedia. Transaksi cepat dan langsung terkonfirmasi.</p>
      </div>

      <!-- Kartu 3: BPJS -->
      <div style="background:#fff;border:1.5px solid #e2e8f0;border-radius:16px;padding:28px 24px;">
        <div style="width:52px;height:52px;background:#dbeafe;border-radius:14px;display:flex;align-items:center;justify-content:center;margin-bottom:16px;">
          <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        </div>
        <div style="display:inline-block;background:#dcfce7;color:#166534;font-size:11px;font-weight:600;padding:3px 10px;border-radius:99px;margin-bottom:10px;">Tersedia 24/7</div>
        <h3 style="font-size:17px;font-weight:700;color:#1e293b;margin:0 0 8px;">BPJS Kesehatan &amp; Ketenagakerjaan</h3>
        <p style="color:#64748b;font-size:14px;line-height:1.6;margin:0;">Bayar iuran BPJS Kesehatan dan BPJS Ketenagakerjaan dengan mudah tanpa antrian.</p>
      </div>

      <!-- Kartu 4: E-Wallet -->
      <div style="background:#fff;border:1.5px solid #e2e8f0;border-radius:16px;padding:28px 24px;">
        <div style="width:52px;height:52px;background:#fce7f3;border-radius:14px;display:flex;align-items:center;justify-content:center;margin-bottom:16px;">
          <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#db2777" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12V7H5a2 2 0 0 1 0-4h14v4"/><path d="M3 5v14a2 2 0 0 0 2 2h16v-5"/><path d="M18 12a2 2 0 0 0 0 4h4v-4z"/></svg>
        </div>
        <div style="display:inline-block;background:#dcfce7;color:#166534;font-size:11px;font-weight:600;padding:3px 10px;border-radius:99px;margin-bottom:10px;">Tersedia 24/7</div>
        <h3 style="font-size:17px;font-weight:700;color:#1e293b;margin:0 0 8px;">Top-Up E-Wallet</h3>
        <p style="color:#64748b;font-size:14px;line-height:1.6;margin:0;">Isi saldo GoPay, OVO, Dana, ShopeePay, dan LinkAja. Proses instan tanpa biaya tambahan.</p>
      </div>

      <!-- Kartu 5: Game -->
      <div style="background:#fff;border:1.5px solid #e2e8f0;border-radius:16px;padding:28px 24px;">
        <div style="width:52px;height:52px;background:#ede9fe;border-radius:14px;display:flex;align-items:center;justify-content:center;margin-bottom:16px;">
          <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="6" y1="12" x2="10" y2="12"/><line x1="8" y1="10" x2="8" y2="14"/><line x1="15" y1="13" x2="15.01" y2="13"/><line x1="18" y1="11" x2="18.01" y2="11"/><rect x="2" y="8" width="20" height="12" rx="2"/></svg>
        </div>
        <div style="display:inline-block;background:#dcfce7;color:#166534;font-size:11px;font-weight:600;padding:3px 10px;border-radius:99px;margin-bottom:10px;">Tersedia 24/7</div>
        <h3 style="font-size:17px;font-weight:700;color:#1e293b;margin:0 0 8px;">Top-Up Game</h3>
        <p style="color:#64748b;font-size:14px;line-height:1.6;margin:0;">Mobile Legends, Free Fire, PUBG, Genshin Impact, Valorant, dan 100+ game populer lainnya.</p>
      </div>

      <!-- Kartu 6: Tagihan -->
      <div style="background:#fff;border:1.5px solid #e2e8f0;border-radius:16px;padding:28px 24px;">
        <div style="width:52px;height:52px;background:#fff7ed;border-radius:14px;display:flex;align-items:center;justify-content:center;margin-bottom:16px;">
          <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#ea580c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
        </div>
        <div style="display:inline-block;background:#dcfce7;color:#166534;font-size:11px;font-weight:600;padding:3px 10px;border-radius:99px;margin-bottom:10px;">Tersedia 24/7</div>
        <h3 style="font-size:17px;font-weight:700;color:#1e293b;margin:0 0 8px;">Tagihan &amp; Angsuran</h3>
        <p style="color:#64748b;font-size:14px;line-height:1.6;margin:0;">PDAM, internet, TV kabel, multifinance, dan cicilan kredit. Satu platform untuk semua tagihan.</p>
      </div>

      <!-- Kartu 7: Bank -->
      <div style="background:#fff;border:1.5px solid #e2e8f0;border-radius:16px;padding:28px 24px;">
        <div style="width:52px;height:52px;background:#cffafe;border-radius:14px;display:flex;align-items:center;justify-content:center;margin-bottom:16px;">
          <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#0891b2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="22" x2="21" y2="22"/><line x1="6" y1="18" x2="6" y2="11"/><line x1="10" y1="18" x2="10" y2="11"/><line x1="14" y1="18" x2="14" y2="11"/><line x1="18" y1="18" x2="18" y2="11"/><polygon points="12 2 20 7 4 7"/></svg>
        </div>
        <div style="display:inline-block;background:#dcfce7;color:#166534;font-size:11px;font-weight:600;padding:3px 10px;border-radius:99px;margin-bottom:10px;">Tersedia 24/7</div>
        <h3 style="font-size:17px;font-weight:700;color:#1e293b;margin:0 0 8px;">Transfer &amp; Pembayaran Bank</h3>
        <p style="color:#64748b;font-size:14px;line-height:1.6;margin:0;">Transfer ke 100+ bank di Indonesia. BCA, BRI, BNI, Mandiri, dan semua bank lokal lainnya.</p>
      </div>

      <!-- Kartu 8: Voucher -->
      <div style="background:#fff;border:1.5px solid #e2e8f0;border-radius:16px;padding:28px 24px;">
        <div style="width:52px;height:52px;background:#f0fdf4;border-radius:14px;display:flex;align-items:center;justify-content:center;margin-bottom:16px;">
          <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#3D9E7A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 12v10H4V12"/><path d="M2 7h20v5H2z"/><path d="M12 22V7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/></svg>
        </div>
        <div style="display:inline-block;background:#dcfce7;color:#166534;font-size:11px;font-weight:600;padding:3px 10px;border-radius:99px;margin-bottom:10px;">Tersedia 24/7</div>
        <h3 style="font-size:17px;font-weight:700;color:#1e293b;margin:0 0 8px;">Voucher &amp; Tiket</h3>
        <p style="color:#64748b;font-size:14px;line-height:1.6;margin:0;">Google Play, App Store, Steam, Netflix, Spotify, tiket konser, dan berbagai voucher digital lainnya.</p>
      </div>

    </div>
  </div>
</section>

<!-- STATS / KEUNGGULAN SECTION -->
<section style="padding:80px 20px;background:#f8fafc;">
  <div style="max-width:1100px;margin:0 auto;">
    <div style="text-align:center;margin-bottom:48px;">
      <h2 style="font-size:clamp(22px,4vw,36px);font-weight:800;color:#1e293b;margin:0 0 12px;">Keunggulan MonetraPay</h2>
      <p style="color:#64748b;font-size:16px;">Platform terpercaya yang sudah dipercaya jutaan transaksi</p>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:24px;">
      <div style="background:#fff;border-radius:16px;padding:32px 24px;text-align:center;box-shadow:0 1px 3px rgba(0,0,0,.06);">
        <div style="font-size:clamp(28px,4vw,42px);font-weight:800;color:#3D9E7A;margin-bottom:8px;">500K+</div>
        <div style="font-size:15px;font-weight:600;color:#1e293b;margin-bottom:4px;">Agen Aktif</div>
        <div style="font-size:13px;color:#64748b;">Di seluruh Indonesia</div>
      </div>
      <div style="background:#fff;border-radius:16px;padding:32px 24px;text-align:center;box-shadow:0 1px 3px rgba(0,0,0,.06);">
        <div style="font-size:clamp(28px,4vw,42px);font-weight:800;color:#3D9E7A;margin-bottom:8px;">100+</div>
        <div style="font-size:15px;font-weight:600;color:#1e293b;margin-bottom:4px;">Produk Tersedia</div>
        <div style="font-size:13px;color:#64748b;">Terus bertambah setiap bulan</div>
      </div>
      <div style="background:#fff;border-radius:16px;padding:32px 24px;text-align:center;box-shadow:0 1px 3px rgba(0,0,0,.06);">
        <div style="font-size:clamp(28px,4vw,42px);font-weight:800;color:#3D9E7A;margin-bottom:8px;">99.9%</div>
        <div style="font-size:15px;font-weight:600;color:#1e293b;margin-bottom:4px;">Uptime</div>
        <div style="font-size:13px;color:#64748b;">Server yang selalu stabil</div>
      </div>
      <div style="background:#fff;border-radius:16px;padding:32px 24px;text-align:center;box-shadow:0 1px 3px rgba(0,0,0,.06);">
        <div style="font-size:clamp(28px,4vw,42px);font-weight:800;color:#3D9E7A;margin-bottom:8px;">24/7</div>
        <div style="font-size:15px;font-weight:600;color:#1e293b;margin-bottom:4px;">Customer Support</div>
        <div style="font-size:13px;color:#64748b;">Siap membantu kapan saja</div>
      </div>
    </div>
  </div>
</section>

<!-- CTA SECTION -->
<section style="padding:80px 20px;background:#3D9E7A;">
  <div style="max-width:700px;margin:0 auto;text-align:center;">
    <h2 style="font-size:clamp(24px,4vw,38px);font-weight:800;color:#fff;margin:0 0 16px;">Siap Mulai Bisnis Digital?</h2>
    <p style="color:rgba(255,255,255,.85);font-size:17px;margin:0 0 32px;">Bergabung bersama 500.000+ agen yang sudah sukses bersama MonetraPay</p>
    <a href="/kontak" style="display:inline-block;background:#fff;color:#3D9E7A;padding:16px 40px;border-radius:12px;font-size:17px;font-weight:800;text-decoration:none;">Daftar Jadi Agen Sekarang</a>
  </div>
</section>

</div>
HTML;
    }
}
