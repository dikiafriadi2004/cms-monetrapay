<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class KontakPageSeeder extends Seeder
{
    public function run(): void
    {
        $css = "@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap'); * { box-sizing: border-box; } a { text-decoration: none; } .kontak-hero { position: relative; overflow: hidden; background: #fff; padding: 48px 0 40px; } .kontak-form-card { background: #fff; border-radius: 16px; padding: 32px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); border: 1px solid #f1f5f9; } .kontak-input { width: 100%; padding: 11px 14px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 14px; font-family: inherit; outline: none; transition: border-color .2s; box-sizing: border-box; } .kontak-input:focus { border-color: #3D9E7A; } .kontak-info-card { display: flex; align-items: flex-start; gap: 14px; background: #f8fafc; border-radius: 12px; padding: 16px; border: 1px solid #e2e8f0; } .kontak-submit-btn { width: 100%; background: #3D9E7A; color: #fff; padding: 14px 24px; border: none; border-radius: 10px; font-size: 15px; font-weight: 700; cursor: pointer; font-family: inherit; transition: background .2s; } .kontak-submit-btn:hover { background: #2d7a5e; }";

        $html = $this->kontakHtml();

        $page = Page::firstOrCreate(
            ['slug' => 'kontak'],
            [
                'title'    => 'Kontak',
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

        $this->command->info('✅ KontakPageSeeder: halaman "kontak" diperbarui ke GrapesJS page builder.');
    }

    private function kontakHtml(): string
    {
        return <<<'HTML'
<div style="font-family:'Plus Jakarta Sans',-apple-system,BlinkMacSystemFont,sans-serif;color:#334155;margin:0;padding:0;">

<!-- SECTION 1: HERO -->
<section class="kontak-hero">
  <!-- decorative shapes -->
  <div style="position:absolute;top:-40px;right:-40px;width:180px;height:180px;border-radius:50%;background:rgba(61,158,122,.10);pointer-events:none;"></div>
  <div style="position:absolute;bottom:-30px;left:-30px;width:120px;height:120px;border-radius:50%;background:rgba(241,196,15,.20);pointer-events:none;"></div>
  <div style="max-width:1100px;margin:0 auto;padding:0 20px;position:relative;">
    <!-- breadcrumb -->
    <nav style="font-size:13px;color:#64748b;margin-bottom:24px;">
      <a href="/" style="color:#64748b;">Beranda</a>
      <span style="margin:0 6px;">&#47;</span>
      <span style="color:#3D9E7A;font-weight:600;">Kontak</span>
    </nav>
    <h1 style="font-size:clamp(28px,5vw,44px);font-weight:800;color:#1e293b;line-height:1.2;margin:0 0 16px;">Ada yang bisa kami <span style="color:#3D9E7A;">bantu?</span></h1>
    <p style="color:#64748b;font-size:clamp(14px,2vw,16px);max-width:520px;margin:0;">Kirim pertanyaan, kendala transaksi, atau ajakan kerja sama. Tim kami akan merespons secepat mungkin.</p>
  </div>
</section>

<!-- SECTION 2: FORM + INFO KONTAK -->
<section style="padding:60px 20px;background:#f8fafc;">
  <div style="max-width:1100px;margin:0 auto;">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:40px;align-items:start;">

      <!-- KOLOM KIRI: FORM -->
      <div class="kontak-form-card">
        <h2 style="font-size:20px;font-weight:800;color:#1e293b;margin:0 0 6px;">Kirim Pesan</h2>
        <p style="color:#64748b;font-size:14px;margin:0 0 24px;">Isi formulir di bawah ini dan kami akan menghubungi Anda kembali.</p>

        <form action="/kontak" method="POST">
          @csrf
          <input type="hidden" name="_t" id="_form_time" value="0">
          <input type="text" name="website" style="display:none;" tabindex="-1" autocomplete="off">

          <!-- Row 1: Nama + Email -->
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px;">
            <div>
              <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px;">Nama Lengkap <span style="color:#ef4444;">*</span></label>
              <input type="text" name="name" required placeholder="Nama Anda" class="kontak-input">
            </div>
            <div>
              <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px;">Email</label>
              <input type="email" name="email" placeholder="nama@email.com" class="kontak-input">
            </div>
          </div>

          <!-- Row 2: No. WA + Topik (as city) -->
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px;">
            <div>
              <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px;">Nomor WhatsApp <span style="color:#ef4444;">*</span></label>
              <input type="tel" name="phone" required placeholder="08xx-xxxx-xxxx" class="kontak-input">
            </div>
            <div>
              <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px;">Topik</label>
              <select name="city" class="kontak-input" style="background:#fff;cursor:pointer;">
                <option value="">-- Pilih Topik --</option>
                <option value="Bantuan transaksi">Bantuan transaksi</option>
                <option value="Daftar Agen/Reseller">Daftar Agen/Reseller</option>
                <option value="Kendala teknis">Kendala teknis</option>
                <option value="Kerja sama">Kerja sama</option>
                <option value="Lainnya">Lainnya</option>
              </select>
            </div>
          </div>

          <!-- Pesan -->
          <div style="margin-bottom:20px;">
            <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px;">Pesan</label>
            <textarea name="message" rows="4" placeholder="Tulis pesan Anda di sini..." class="kontak-input" style="resize:vertical;"></textarea>
          </div>

          <!-- Submit -->
          <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
            <button type="submit" class="kontak-submit-btn" style="flex:1;min-width:140px;">Kirim Pesan &rarr;</button>
            <p style="font-size:12px;color:#94a3b8;margin:0;flex:1;min-width:140px;">Dengan mengirim, Anda menyetujui <a href="/kebijakan-privasi" style="color:#3D9E7A;">Kebijakan Privasi</a> kami.</p>
          </div>
        </form>

        <script>document.getElementById('_form_time') && (document.getElementById('_form_time').value = Math.floor(Date.now()/1000));</script>
      </div>

      <!-- KOLOM KANAN: INFO KONTAK (4 card vertikal) -->
      <div style="display:flex;flex-direction:column;gap:14px;">

        <!-- Card Email -->
        <div class="kontak-info-card">
          <div style="width:42px;height:42px;background:#dbeafe;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
          </div>
          <div>
            <div style="font-size:12px;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin-bottom:2px;">Email</div>
            <div style="font-size:14px;font-weight:600;color:#1e293b;"><a href="mailto:support@monetrapay.co.id" style="color:#2563eb;">support@monetrapay.co.id</a></div>
          </div>
        </div>

        <!-- Card WhatsApp -->
        <div class="kontak-info-card">
          <div style="width:42px;height:42px;background:#dcfce7;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="#25D366" stroke="none"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
          </div>
          <div>
            <div style="font-size:12px;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin-bottom:2px;">WhatsApp</div>
            <div style="font-size:14px;font-weight:600;color:#1e293b;"><a href="https://wa.me/6281234567890" target="_blank" style="color:#16a34a;">+62 812-3456-7890</a></div>
          </div>
        </div>

        <!-- Card Jam Layanan -->
        <div class="kontak-info-card">
          <div style="width:42px;height:42px;background:#fef9c3;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ca8a04" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          </div>
          <div>
            <div style="font-size:12px;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin-bottom:2px;">Jam Layanan</div>
            <div style="font-size:14px;font-weight:600;color:#1e293b;">Senin - Minggu, 00.00 - 24.00 WIB</div>
          </div>
        </div>

        <!-- Card Kantor -->
        <div class="kontak-info-card">
          <div style="width:42px;height:42px;background:#fee2e2;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
          </div>
          <div>
            <div style="font-size:12px;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin-bottom:2px;">Kantor</div>
            <div style="font-size:14px;font-weight:600;color:#1e293b;">Jl. Gatot Subroto No. 123,<br>Jakarta Selatan, 12930</div>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>

<!-- SECTION 3: PETA LOKASI -->
<section style="padding:60px 20px;background:#f8fafc;position:relative;overflow:hidden;">
  <!-- decorative shapes -->
  <div style="position:absolute;top:-30px;left:-30px;width:120px;height:120px;border-radius:50%;background:rgba(61,158,122,.10);pointer-events:none;"></div>
  <div style="position:absolute;bottom:-40px;right:-40px;width:160px;height:160px;border-radius:50%;background:rgba(241,196,15,.15);pointer-events:none;"></div>
  <div style="max-width:1100px;margin:0 auto;position:relative;">
    <div style="text-align:center;margin-bottom:28px;">
      <h2 style="font-size:clamp(20px,4vw,28px);font-weight:800;color:#1e293b;margin:0 0 8px;">Lokasi Kami</h2>
      <p style="color:#64748b;font-size:14px;margin:0;">Jl. Gatot Subroto No. 123, Jakarta Selatan, 12930</p>
    </div>
    <iframe src="https://maps.google.com/maps?q=-6.2088,106.8456&z=15&output=embed" width="100%" height="350" style="border:0;border-radius:12px;display:block;" allowfullscreen="" loading="lazy" title="Lokasi Kantor MonetraPay"></iframe>
  </div>
</section>

<!-- SECTION 4: CTA -->
<section style="padding:60px 20px;background:#fff;">
  <div style="max-width:1100px;margin:0 auto;">
    <div style="display:grid;grid-template-columns:1fr auto;gap:32px;align-items:center;background:#f8fafc;border-radius:16px;padding:40px;border:1px solid #e2e8f0;">
      <div>
        <h2 style="font-size:clamp(18px,3vw,26px);font-weight:800;color:#1e293b;margin:0 0 10px;">Ingin jadi mitra MonetraPay?</h2>
        <p style="color:#64748b;font-size:15px;margin:0;max-width:520px;">Pilih topik &ldquo;Kerja sama&rdquo; pada formulir, dan tim kami akan menjelaskan skema harga serta cara bergabung.</p>
      </div>
      <div style="flex-shrink:0;">
        <a href="/tentang-kami" style="display:inline-block;padding:14px 28px;border:2px solid #3D9E7A;border-radius:10px;color:#3D9E7A;font-size:15px;font-weight:700;text-decoration:none;white-space:nowrap;transition:all .2s;" onmouseover="this.style.background='#3D9E7A';this.style.color='#fff';" onmouseout="this.style.background='transparent';this.style.color='#3D9E7A';">Pelajari tentang kami &rarr;</a>
      </div>
    </div>
  </div>
</section>

</div>
HTML;
    }
}
