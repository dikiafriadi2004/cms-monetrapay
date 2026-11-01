<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class DefaultPagesSeeder extends Seeder
{
    private string $baseCss = "@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap'); * { box-sizing: border-box; } a { transition: opacity .2s; } a:hover { opacity: .85; }";

    public function run(): void
    {
        // ── 1. HALAMAN KONTAK ────────────────────────────────────────
        $kontakCss = "@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap'); * { box-sizing: border-box; } input:focus, textarea:focus, select:focus { border-color: #0D9488 !important; box-shadow: 0 0 0 3px rgba(13,148,136,.1); }";

        Page::updateOrCreate(
            ['slug' => 'kontak'],
            [
                'title'            => 'Kontak',
                'status'           => 'published',
                'template'         => 'default',
                'use_page_builder' => false,
                'meta_title'       => 'Hubungi Kami — MonetraPay',
                'meta_description' => 'Hubungi tim MonetraPay untuk pertanyaan, pendaftaran agen, atau informasi layanan.',
                'gjs_css'          => $kontakCss,
                'gjs_components'   => null,
                'content'          => $this->kontakHtml(),
            ]
        );

        // ── 2. HALAMAN TENTANG KAMI ──────────────────────────────────
        Page::updateOrCreate(
            ['slug' => 'tentang-kami'],
            [
                'title'            => 'Tentang Kami',
                'status'           => 'published',
                'template'         => 'default',
                'use_page_builder' => false,
                'meta_title'       => 'Tentang MonetraPay — Platform Transaksi Digital Terpercaya',
                'meta_description' => 'MonetraPay adalah platform transaksi digital terpercaya untuk agen pulsa, PPOB, top-up game, dan e-wallet di Indonesia.',
                'gjs_css'          => $this->baseCss,
                'gjs_components'   => null,
                'content'          => $this->tentangHtml(),
            ]
        );

        // ── 3. HALAMAN LAYANAN ───────────────────────────────────────
        Page::updateOrCreate(
            ['slug' => 'layanan'],
            [
                'title'            => 'Layanan',
                'status'           => 'published',
                'template'         => 'default',
                'use_page_builder' => false,
                'meta_title'       => 'Layanan MonetraPay — Pulsa, PPOB, Game & E-Wallet',
                'meta_description' => 'Semua kebutuhan transaksi digital dalam satu platform: pulsa, PPOB, top-up game, dan e-wallet.',
                'gjs_css'          => $this->baseCss,
                'gjs_components'   => null,
                'content'          => $this->layananHtml(),
            ]
        );

        $this->command->info('✅ Default pages seeded with GrapesJS content: kontak, tentang-kami, layanan');
    }

    private function kontakHtml(): string
    {
        return <<<'HTML'
<div style="font-family:'Plus Jakarta Sans',-apple-system,sans-serif;color:#334155;margin:0;padding:0;">

<!-- HERO -->
<section style="background:#f0fdf4;padding:60px 20px 80px;position:relative;overflow:hidden;">
  <div style="position:absolute;top:-60px;right:-60px;width:260px;height:260px;background:#bbf7d0;border-radius:50%;opacity:.5;"></div>
  <div style="position:absolute;bottom:-40px;left:40px;width:120px;height:120px;background:#fed7aa;border-radius:50%;opacity:.5;"></div>
  <div style="max-width:1100px;margin:0 auto;position:relative;">
    <nav style="font-size:13px;color:#64748b;margin-bottom:24px;">
      <a href="/" style="color:#64748b;text-decoration:none;">Beranda</a>
      <span style="margin:0 6px;">/</span>
      <span style="color:#0D9488;">Kontak</span>
    </nav>
    <div style="max-width:560px;">
      <h1 style="font-size:2.6rem;font-weight:800;color:#0f172a;line-height:1.15;margin:0 0 16px;">Ada yang bisa kami <span style="color:#0D9488;">bantu?</span></h1>
      <p style="font-size:1rem;color:#64748b;line-height:1.8;margin:0;">Kirim pertanyaan, kendala transaksi, atau ajakan kerja sama. Tim kami akan merespons secepat mungkin.</p>
    </div>
  </div>
</section>

<!-- FORM + INFO -->
<section style="background:#fff;padding:60px 20px;">
  <div style="max-width:1100px;margin:0 auto;display:flex;gap:32px;flex-wrap:wrap;align-items:flex-start;">
    <div style="flex:1;min-width:300px;background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:36px;box-shadow:0 2px 16px rgba(0,0,0,.06);">
      <h2 style="font-size:1.4rem;font-weight:800;color:#0f172a;margin:0 0 6px;">Kirim pesan</h2>
      <p style="font-size:13px;color:#64748b;margin:0 0 28px;">Isi formulir di bawah ini dan kami akan menghubungi Anda kembali.</p>
      <form action="/kontak" method="POST">
        @csrf
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
          <div>
            <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:5px;">Nama lengkap</label>
            <input type="text" name="name" placeholder="Nama Anda" required style="width:100%;padding:10px 12px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:14px;outline:none;font-family:inherit;">
          </div>
          <div>
            <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:5px;">Email</label>
            <input type="email" name="email" placeholder="nama@email.com" required style="width:100%;padding:10px 12px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:14px;outline:none;font-family:inherit;">
          </div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
          <div>
            <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:5px;">Nomor WhatsApp</label>
            <input type="tel" name="phone" placeholder="08xx-xxxx-xxxx" style="width:100%;padding:10px 12px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:14px;outline:none;font-family:inherit;">
          </div>
          <div>
            <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:5px;">Topik</label>
            <select name="subject" style="width:100%;padding:10px 12px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:14px;outline:none;font-family:inherit;background:#fff;">
              <option>Bantuan transaksi</option>
              <option>Kemitraan &amp; reseller</option>
              <option>Pertanyaan umum</option>
              <option>Laporan masalah</option>
              <option>Lainnya</option>
            </select>
          </div>
        </div>
        <div style="margin-bottom:24px;">
          <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:5px;">Pesan</label>
          <textarea name="message" rows="5" placeholder="Tulis pesan Anda di sini..." required style="width:100%;padding:10px 12px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:14px;outline:none;resize:vertical;font-family:inherit;"></textarea>
        </div>
        <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
          <button type="submit" style="background:#F59E0B;color:#fff;border:none;padding:12px 28px;border-radius:8px;font-size:14px;font-weight:700;cursor:pointer;font-family:inherit;">Kirim Pesan</button>
          <p style="font-size:12px;color:#94a3b8;margin:0;line-height:1.5;">Dengan mengirim, Anda menyetujui <a href="/kebijakan-privasi" style="color:#0D9488;text-decoration:none;">Kebijakan Privasi</a> kami.</p>
        </div>
      </form>
    </div>
    <div style="flex:0 0 300px;min-width:260px;display:flex;flex-direction:column;gap:12px;">
      <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:20px;display:flex;align-items:center;gap:16px;">
        <div style="width:40px;height:40px;background:#e6f7f5;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:18px;">✉️</div>
        <div><div style="font-size:13px;font-weight:700;color:#0f172a;margin-bottom:2px;">Email</div><div style="font-size:13px;color:#64748b;">support@monetrapay.com</div></div>
      </div>
      <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:20px;display:flex;align-items:center;gap:16px;">
        <div style="width:40px;height:40px;background:#dcfce7;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:18px;">💬</div>
        <div><div style="font-size:13px;font-weight:700;color:#0f172a;margin-bottom:2px;">WhatsApp</div><div style="font-size:13px;color:#64748b;">+6281234567890</div></div>
      </div>
      <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:20px;display:flex;align-items:center;gap:16px;">
        <div style="width:40px;height:40px;background:#fef9c3;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:18px;">🕐</div>
        <div><div style="font-size:13px;font-weight:700;color:#0f172a;margin-bottom:2px;">Jam Layanan</div><div style="font-size:13px;color:#64748b;">Senin - Minggu, 08.00 - 24.00 WIB</div></div>
      </div>
      <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:20px;display:flex;align-items:center;gap:16px;">
        <div style="width:40px;height:40px;background:#ede9fe;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:18px;">📍</div>
        <div><div style="font-size:13px;font-weight:700;color:#0f172a;margin-bottom:2px;">Kantor</div><div style="font-size:13px;color:#64748b;">Jakarta Selatan, DKI Jakarta 12190</div></div>
      </div>
    </div>
  </div>
</section>

<!-- GOOGLE MAPS -->
<section style="background:#f0fdf4;padding:60px 20px;position:relative;overflow:hidden;">
  <div style="position:absolute;top:-60px;right:80px;width:200px;height:200px;background:#fed7aa;border-radius:50%;opacity:.4;"></div>
  <div style="max-width:1100px;margin:0 auto;position:relative;">
    <div style="background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,.08);">
      <iframe src="https://maps.google.com/maps?q=-6.2088,106.8456&z=15&output=embed" width="100%" height="360" style="border:0;display:block;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
  </div>
</section>

<!-- CTA MITRA -->
<section style="background:#fff;padding:60px 20px;border-top:1px solid #e2e8f0;">
  <div style="max-width:1100px;margin:0 auto;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:24px;">
    <div>
      <h2 style="font-size:1.6rem;font-weight:800;color:#0f172a;margin:0 0 8px;">Ingin jadi mitra MonetraPay?</h2>
      <p style="font-size:14px;color:#64748b;margin:0;max-width:500px;line-height:1.7;">Pilih topik "Kemitraan &amp; reseller" pada formulir, dan tim kami akan menjelaskan skema harga serta cara bergabung.</p>
    </div>
    <a href="/tentang-kami" style="display:inline-block;background:#fff;color:#0D9488;padding:12px 28px;border-radius:10px;font-weight:700;font-size:14px;text-decoration:none;border:2px solid #0D9488;white-space:nowrap;">Pelajari tentang kami</a>
  </div>
</section>

</div>
HTML;
    }

    private function tentangHtml(): string
    {
        return <<<'HTML'
<div style="font-family:'Plus Jakarta Sans',sans-serif;color:#334155;margin:0;padding:0;">

<!-- HERO -->
<section style="background:#f8fafc;padding:80px 20px;">
  <div style="max-width:1100px;margin:0 auto;display:flex;gap:60px;align-items:center;flex-wrap:wrap;">
    <div style="flex:1;min-width:280px;">
      <span style="display:inline-block;background:#e6f7f5;color:#0D9488;font-size:12px;font-weight:700;padding:6px 14px;border-radius:99px;letter-spacing:1px;text-transform:uppercase;margin-bottom:20px;">Tentang Kami</span>
      <h1 style="font-size:2.8rem;font-weight:800;color:#0f172a;line-height:1.15;margin:0 0 20px;">Membuat pembayaran digital terasa <span style="color:#0D9488;">mudah untuk semua orang.</span></h1>
      <p style="font-size:1.1rem;color:#64748b;line-height:1.8;margin:0 0 40px;">MonetraPay hadir untuk mempermudah masyarakat Indonesia dalam bertransaksi digital. Kami percaya bahwa layanan keuangan digital seharusnya dapat diakses oleh siapa saja, di mana saja.</p>
      <div style="display:flex;gap:32px;flex-wrap:wrap;">
        <div style="text-align:center;"><div style="font-size:2rem;font-weight:800;color:#0D9488;">100K+</div><div style="font-size:13px;color:#64748b;margin-top:4px;">Pengguna Aktif</div></div>
        <div style="text-align:center;"><div style="font-size:2rem;font-weight:800;color:#0D9488;">500+</div><div style="font-size:13px;color:#64748b;margin-top:4px;">Produk Digital</div></div>
        <div style="text-align:center;"><div style="font-size:2rem;font-weight:800;color:#0D9488;">99.9%</div><div style="font-size:13px;color:#64748b;margin-top:4px;">Uptime</div></div>
        <div style="text-align:center;"><div style="font-size:2rem;font-weight:800;color:#F59E0B;">2019</div><div style="font-size:13px;color:#64748b;margin-top:4px;">Tahun Berdiri</div></div>
      </div>
    </div>
    <div style="flex:0 0 360px;display:flex;justify-content:center;">
      <div style="width:320px;height:320px;background:#e6f7f5;border-radius:24px;display:flex;align-items:center;justify-content:center;position:relative;">
        <div style="font-size:3rem;font-weight:800;"><span style="color:#0D9488;">Monetra</span><span style="color:#F59E0B;">Pay</span></div>
        <div style="position:absolute;top:-12px;right:-12px;width:64px;height:64px;background:#fff7ed;border-radius:12px;opacity:.8;"></div>
        <div style="position:absolute;bottom:-10px;left:-10px;width:48px;height:48px;background:#e6f7f5;border-radius:10px;opacity:.8;"></div>
      </div>
    </div>
  </div>
</section>

<!-- VISI MISI -->
<section style="background:#f8fafc;padding:80px 20px;">
  <div style="max-width:1100px;margin:0 auto;display:grid;grid-template-columns:1fr 1fr;gap:24px;">
    <div style="background:#0D9488;border-radius:24px;padding:40px;color:#fff;position:relative;overflow:hidden;">
      <div style="position:absolute;top:-40px;right:-40px;width:160px;height:160px;background:rgba(255,255,255,.06);border-radius:50%;"></div>
      <div style="width:48px;height:48px;background:rgba(255,255,255,.2);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:22px;margin-bottom:20px;">👁️</div>
      <h3 style="font-size:1.5rem;font-weight:800;margin:0 0 16px;">Visi</h3>
      <p style="line-height:1.8;opacity:.9;margin:0;">Menjadi platform transaksi digital terdepan yang menghubungkan jutaan masyarakat Indonesia dengan layanan keuangan digital yang mudah, cepat, dan terjangkau.</p>
    </div>
    <div style="background:#fff;border-radius:24px;padding:40px;border:1px solid #e2e8f0;">
      <div style="width:48px;height:48px;background:#fff7ed;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:22px;margin-bottom:20px;">🎯</div>
      <h3 style="font-size:1.5rem;font-weight:800;color:#0f172a;margin:0 0 20px;">Misi</h3>
      <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:12px;">
        <li style="display:flex;gap:12px;align-items:flex-start;"><span style="width:20px;height:20px;background:#e6f7f5;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:11px;color:#0D9488;flex-shrink:0;margin-top:2px;">✓</span><span style="color:#475569;font-size:14px;line-height:1.6;">Menyediakan akses layanan digital yang mudah dan terjangkau bagi semua kalangan.</span></li>
        <li style="display:flex;gap:12px;align-items:flex-start;"><span style="width:20px;height:20px;background:#e6f7f5;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:11px;color:#0D9488;flex-shrink:0;margin-top:2px;">✓</span><span style="color:#475569;font-size:14px;line-height:1.6;">Membangun ekosistem agen yang kuat dan merata di seluruh Indonesia.</span></li>
        <li style="display:flex;gap:12px;align-items:flex-start;"><span style="width:20px;height:20px;background:#e6f7f5;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:11px;color:#0D9488;flex-shrink:0;margin-top:2px;">✓</span><span style="color:#475569;font-size:14px;line-height:1.6;">Berinovasi terus-menerus untuk menghadirkan produk digital terbaik.</span></li>
        <li style="display:flex;gap:12px;align-items:flex-start;"><span style="width:20px;height:20px;background:#e6f7f5;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:11px;color:#0D9488;flex-shrink:0;margin-top:2px;">✓</span><span style="color:#475569;font-size:14px;line-height:1.6;">Menjaga kepercayaan pengguna dengan sistem yang aman dan transparan.</span></li>
      </ul>
    </div>
  </div>
</section>

<!-- NILAI-NILAI -->
<section style="background:#fff;padding:80px 20px;">
  <div style="max-width:1100px;margin:0 auto;">
    <div style="text-align:center;margin-bottom:56px;">
      <span style="display:inline-block;background:#e6f7f5;color:#0D9488;font-size:12px;font-weight:700;padding:6px 14px;border-radius:99px;letter-spacing:1px;text-transform:uppercase;margin-bottom:16px;">Nilai Kami</span>
      <h2 style="font-size:2rem;font-weight:800;color:#0f172a;margin:0;">Prinsip yang kami pegang</h2>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:20px;">
      <div style="background:#f8fafc;border-radius:16px;padding:28px;border:1px solid #e2e8f0;"><div style="font-size:2rem;margin-bottom:12px;">🤝</div><div style="font-weight:700;color:#0f172a;margin-bottom:8px;">Transparansi</div><div style="font-size:13px;color:#64748b;line-height:1.6;">Kami percaya pada keterbukaan informasi dan komunikasi yang jujur kepada seluruh pemangku kepentingan.</div></div>
      <div style="background:#f8fafc;border-radius:16px;padding:28px;border:1px solid #e2e8f0;"><div style="font-size:2rem;margin-bottom:12px;">🛡️</div><div style="font-weight:700;color:#0f172a;margin-bottom:8px;">Dapat Dipercaya</div><div style="font-size:13px;color:#64748b;line-height:1.6;">Keamanan dan kepercayaan pengguna adalah prioritas utama dalam setiap keputusan yang kami buat.</div></div>
      <div style="background:#f8fafc;border-radius:16px;padding:28px;border:1px solid #e2e8f0;"><div style="font-size:2rem;margin-bottom:12px;">💡</div><div style="font-weight:700;color:#0f172a;margin-bottom:8px;">Fokus Pengguna</div><div style="font-size:13px;color:#64748b;line-height:1.6;">Setiap fitur dan layanan kami dirancang dengan mengutamakan kemudahan dan kebutuhan pengguna.</div></div>
      <div style="background:#f8fafc;border-radius:16px;padding:28px;border:1px solid #e2e8f0;"><div style="font-size:2rem;margin-bottom:12px;">🌱</div><div style="font-weight:700;color:#0f172a;margin-bottom:8px;">Terus Berkembang</div><div style="font-size:13px;color:#64748b;line-height:1.6;">Kami terus belajar, berinovasi, dan berkembang untuk memberikan layanan terbaik bagi Indonesia.</div></div>
    </div>
  </div>
</section>

<!-- TIM -->
<section style="background:#f8fafc;padding:80px 20px;">
  <div style="max-width:1100px;margin:0 auto;">
    <div style="text-align:center;margin-bottom:56px;">
      <span style="display:inline-block;background:#e6f7f5;color:#0D9488;font-size:12px;font-weight:700;padding:6px 14px;border-radius:99px;letter-spacing:1px;text-transform:uppercase;margin-bottom:16px;">Tim Kami</span>
      <h2 style="font-size:2rem;font-weight:800;color:#0f172a;margin:0;">Orang-orang di balik MonetraPay</h2>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:24px;">
      <div style="text-align:center;"><div style="width:80px;height:80px;background:#0D9488;border-radius:16px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.8rem;font-weight:800;margin:0 auto 16px;">A</div><div style="font-weight:700;color:#0f172a;">Ahmad Fauzi</div><div style="font-size:13px;color:#64748b;margin-top:4px;">CEO & Co-Founder</div></div>
      <div style="text-align:center;"><div style="width:80px;height:80px;background:#F59E0B;border-radius:16px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.8rem;font-weight:800;margin:0 auto 16px;">B</div><div style="font-weight:700;color:#0f172a;">Budi Santoso</div><div style="font-size:13px;color:#64748b;margin-top:4px;">CTO & Co-Founder</div></div>
      <div style="text-align:center;"><div style="width:80px;height:80px;background:#8b5cf6;border-radius:16px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.8rem;font-weight:800;margin:0 auto 16px;">C</div><div style="font-weight:700;color:#0f172a;">Citra Dewi</div><div style="font-size:13px;color:#64748b;margin-top:4px;">Head of Product</div></div>
      <div style="text-align:center;"><div style="width:80px;height:80px;background:#3b82f6;border-radius:16px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.8rem;font-weight:800;margin:0 auto 16px;">D</div><div style="font-weight:700;color:#0f172a;">Dian Pratama</div><div style="font-size:13px;color:#64748b;margin-top:4px;">Head of Operations</div></div>
    </div>
  </div>
</section>

<!-- CTA -->
<section style="background:#0D9488;padding:80px 20px;text-align:center;">
  <div style="max-width:600px;margin:0 auto;">
    <h2 style="font-size:2.2rem;font-weight:800;color:#fff;margin:0 0 16px;">Ingin bekerja sama atau sekadar bertanya?</h2>
    <p style="color:rgba(255,255,255,.8);font-size:1.05rem;line-height:1.7;margin:0 0 32px;">Tim kami siap membantu Anda. Hubungi kami sekarang dan mulai perjalanan digital Anda bersama MonetraPay.</p>
    <a href="/kontak" style="display:inline-block;background:#fff;color:#0D9488;padding:14px 40px;border-radius:12px;font-weight:700;font-size:1rem;text-decoration:none;">Hubungi Kami →</a>
  </div>
</section>

</div>
HTML;
    }

    private function layananHtml(): string
    {
        return <<<'HTML'
<div style="font-family:'Plus Jakarta Sans',sans-serif;color:#334155;margin:0;padding:0;">

<!-- HERO -->
<section style="background:#f8fafc;padding:80px 20px;text-align:center;">
  <div style="max-width:700px;margin:0 auto;">
    <span style="display:inline-block;background:#e6f7f5;color:#0D9488;font-size:12px;font-weight:700;padding:6px 14px;border-radius:99px;letter-spacing:1px;text-transform:uppercase;margin-bottom:20px;">Layanan</span>
    <h1 style="font-size:2.8rem;font-weight:800;color:#0f172a;line-height:1.15;margin:0 0 20px;">Semua Kebutuhan Digital dalam Satu Platform</h1>
    <p style="font-size:1.1rem;color:#64748b;line-height:1.8;margin:0 0 36px;">Transaksi cepat, aman, dan terpercaya untuk berbagai layanan digital. Harga kompetitif, proses instan.</p>
    <div style="display:flex;gap:16px;justify-content:center;flex-wrap:wrap;">
      <a href="/kontak" style="display:inline-block;background:#0D9488;color:#fff;padding:14px 32px;border-radius:10px;font-weight:700;text-decoration:none;">Daftar Jadi Agen</a>
      <a href="#layanan-list" style="display:inline-block;background:#fff;color:#0D9488;padding:14px 32px;border-radius:10px;font-weight:700;text-decoration:none;border:2px solid #0D9488;">Lihat Layanan</a>
    </div>
  </div>
</section>

<!-- SERVICES GRID -->
<section id="layanan-list" style="background:#fff;padding:80px 20px;">
  <div style="max-width:1100px;margin:0 auto;">
    <div style="text-align:center;margin-bottom:48px;">
      <h2 style="font-size:2rem;font-weight:800;color:#0f172a;margin:0 0 12px;">Produk & Layanan Kami</h2>
      <p style="color:#64748b;font-size:1rem;">Lengkap untuk semua kebutuhan transaksi digital Anda</p>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:20px;">
      <div style="background:#f8fafc;border-radius:16px;padding:28px;border:1px solid #e2e8f0;"><div style="width:52px;height:52px;background:#dcfce7;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:24px;margin-bottom:14px;">📱</div><h3 style="font-weight:700;color:#0f172a;margin:0 0 8px;font-size:1rem;">Pulsa All Operator</h3><p style="font-size:13px;color:#64748b;line-height:1.6;margin:0;">Jual pulsa semua operator dengan harga kompetitif. Telkomsel, Indosat, XL, Tri, dan lainnya.</p></div>
      <div style="background:#f8fafc;border-radius:16px;padding:28px;border:1px solid #e2e8f0;"><div style="width:52px;height:52px;background:#dbeafe;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:24px;margin-bottom:14px;">📄</div><h3 style="font-weight:700;color:#0f172a;margin:0 0 8px;font-size:1rem;">PPOB & Tagihan</h3><p style="font-size:13px;color:#64748b;line-height:1.6;margin:0;">Bayar tagihan PLN, PDAM, BPJS, internet, TV kabel, dan berbagai tagihan lainnya.</p></div>
      <div style="background:#f8fafc;border-radius:16px;padding:28px;border:1px solid #e2e8f0;"><div style="width:52px;height:52px;background:#ede9fe;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:24px;margin-bottom:14px;">🎮</div><h3 style="font-weight:700;color:#0f172a;margin:0 0 8px;font-size:1rem;">Top-Up Game</h3><p style="font-size:13px;color:#64748b;line-height:1.6;margin:0;">Top-up diamond ML, UC PUBG, dan voucher 100+ game populer dengan harga terbaik.</p></div>
      <div style="background:#f8fafc;border-radius:16px;padding:28px;border:1px solid #e2e8f0;"><div style="width:52px;height:52px;background:#fff7ed;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:24px;margin-bottom:14px;">💳</div><h3 style="font-weight:700;color:#0f172a;margin:0 0 8px;font-size:1rem;">E-Money & E-Wallet</h3><p style="font-size:13px;color:#64748b;line-height:1.6;margin:0;">Isi saldo GoPay, OVO, DANA, ShopeePay, LinkAja, dan dompet digital lainnya.</p></div>
      <div style="background:#f8fafc;border-radius:16px;padding:28px;border:1px solid #e2e8f0;"><div style="width:52px;height:52px;background:#dcfce7;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:24px;margin-bottom:14px;">💸</div><h3 style="font-weight:700;color:#0f172a;margin:0 0 8px;font-size:1rem;">Transfer Uang</h3><p style="font-size:13px;color:#64748b;line-height:1.6;margin:0;">Transfer ke semua bank dengan biaya rendah dan proses real-time 24 jam sehari.</p></div>
      <div style="background:#f8fafc;border-radius:16px;padding:28px;border:1px solid #e2e8f0;"><div style="width:52px;height:52px;background:#fef9c3;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:24px;margin-bottom:14px;">🎟️</div><h3 style="font-weight:700;color:#0f172a;margin:0 0 8px;font-size:1rem;">Voucher Digital</h3><p style="font-size:13px;color:#64748b;line-height:1.6;margin:0;">Google Play, iTunes, Netflix, Spotify, dan ratusan voucher platform digital lainnya.</p></div>
      <div style="background:#f8fafc;border-radius:16px;padding:28px;border:1px solid #e2e8f0;"><div style="width:52px;height:52px;background:#fce7f3;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:24px;margin-bottom:14px;">🛡️</div><h3 style="font-weight:700;color:#0f172a;margin:0 0 8px;font-size:1rem;">Asuransi Digital</h3><p style="font-size:13px;color:#64748b;line-height:1.6;margin:0;">Produk asuransi jiwa, kesehatan, dan kendaraan dengan premi terjangkau dan klaim mudah.</p></div>
      <div style="background:#f8fafc;border-radius:16px;padding:28px;border:1px solid #e2e8f0;"><div style="width:52px;height:52px;background:#dbeafe;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:24px;margin-bottom:14px;">🏦</div><h3 style="font-weight:700;color:#0f172a;margin:0 0 8px;font-size:1rem;">Pinjaman Digital</h3><p style="font-size:13px;color:#64748b;line-height:1.6;margin:0;">Pinjaman modal usaha dengan proses cepat, bunga rendah, dan tanpa agunan untuk agen aktif.</p></div>
    </div>
  </div>
</section>

<!-- KENAPA MONETRAPAY -->
<section style="background:#0D9488;padding:80px 20px;">
  <div style="max-width:1100px;margin:0 auto;">
    <div style="text-align:center;margin-bottom:48px;">
      <h2 style="font-size:2rem;font-weight:800;color:#fff;margin:0 0 12px;">Kenapa Memilih MonetraPay?</h2>
      <p style="color:rgba(255,255,255,.8);font-size:1rem;">Kami memberikan yang terbaik untuk bisnis Anda</p>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:24px;">
      <div style="background:rgba(255,255,255,.1);border-radius:16px;padding:32px;text-align:center;"><div style="font-size:2.5rem;margin-bottom:12px;">💰</div><h3 style="font-weight:700;color:#fff;margin:0 0 8px;">Harga Terbaik</h3><p style="color:rgba(255,255,255,.8);font-size:13px;line-height:1.6;margin:0;">Margin keuntungan kompetitif untuk semua produk digital.</p></div>
      <div style="background:rgba(255,255,255,.1);border-radius:16px;padding:32px;text-align:center;"><div style="font-size:2.5rem;margin-bottom:12px;">⚡</div><h3 style="font-weight:700;color:#fff;margin:0 0 8px;">Transaksi Cepat</h3><p style="color:rgba(255,255,255,.8);font-size:13px;line-height:1.6;margin:0;">Proses transaksi selesai dalam hitungan detik, 24 jam sehari.</p></div>
      <div style="background:rgba(255,255,255,.1);border-radius:16px;padding:32px;text-align:center;"><div style="font-size:2.5rem;margin-bottom:12px;">🎧</div><h3 style="font-weight:700;color:#fff;margin:0 0 8px;">Support 24/7</h3><p style="color:rgba(255,255,255,.8);font-size:13px;line-height:1.6;margin:0;">Tim CS kami siap membantu kapan saja via WhatsApp dan live chat.</p></div>
    </div>
  </div>
</section>

<!-- CARA KERJA -->
<section style="background:#fff;padding:80px 20px;">
  <div style="max-width:1100px;margin:0 auto;">
    <div style="text-align:center;margin-bottom:56px;">
      <span style="display:inline-block;background:#e6f7f5;color:#0D9488;font-size:12px;font-weight:700;padding:6px 14px;border-radius:99px;letter-spacing:1px;text-transform:uppercase;margin-bottom:16px;">Cara Kerja</span>
      <h2 style="font-size:2rem;font-weight:800;color:#0f172a;margin:0;">Mulai dalam 3 Langkah Mudah</h2>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:32px;">
      <div style="text-align:center;"><div style="width:64px;height:64px;background:#0D9488;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.5rem;font-weight:800;margin:0 auto 20px;box-shadow:0 4px 16px rgba(13,148,136,.3);">1</div><h3 style="font-weight:700;color:#0f172a;margin:0 0 8px;">Daftar Akun</h3><p style="font-size:13px;color:#64748b;line-height:1.6;margin:0 auto;max-width:220px;">Buat akun gratis hanya dengan email dan nomor telepon. Proses kurang dari 2 menit.</p></div>
      <div style="text-align:center;"><div style="width:64px;height:64px;background:#F59E0B;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.5rem;font-weight:800;margin:0 auto 20px;box-shadow:0 4px 16px rgba(245,158,11,.3);">2</div><h3 style="font-weight:700;color:#0f172a;margin:0 0 8px;">Deposit Saldo</h3><p style="font-size:13px;color:#64748b;line-height:1.6;margin:0 auto;max-width:220px;">Top-up saldo mulai dari Rp 10.000 via transfer bank, e-wallet, atau QRIS.</p></div>
      <div style="text-align:center;"><div style="width:64px;height:64px;background:#0D9488;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.5rem;font-weight:800;margin:0 auto 20px;box-shadow:0 4px 16px rgba(13,148,136,.3);">3</div><h3 style="font-weight:700;color:#0f172a;margin:0 0 8px;">Mulai Transaksi</h3><p style="font-size:13px;color:#64748b;line-height:1.6;margin:0 auto;max-width:220px;">Langsung jual pulsa, bayar tagihan, top-up game dengan harga terbaik.</p></div>
    </div>
  </div>
</section>

<!-- CTA -->
<section style="background:#f8fafc;padding:80px 20px;text-align:center;border-top:1px solid #e2e8f0;">
  <div style="max-width:600px;margin:0 auto;">
    <h2 style="font-size:2rem;font-weight:800;color:#0f172a;margin:0 0 16px;">Bergabung sebagai Agen Sekarang</h2>
    <p style="color:#64748b;font-size:1rem;line-height:1.7;margin:0 0 32px;">Mulai bisnis digital Anda bersama MonetraPay. Gratis daftar, proses cepat, margin menguntungkan.</p>
    <a href="/kontak" style="display:inline-block;background:#0D9488;color:#fff;padding:14px 40px;border-radius:12px;font-weight:700;font-size:1rem;text-decoration:none;">Daftar Sekarang →</a>
  </div>
</section>

</div>
HTML;
    }
}
