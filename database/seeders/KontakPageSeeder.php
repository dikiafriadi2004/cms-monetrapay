<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class KontakPageSeeder extends Seeder
{
    public function run(): void
    {
        $css = "@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap'); * { box-sizing: border-box; } input:focus, textarea:focus { border-color: #3D9E7A !important; box-shadow: 0 0 0 3px rgba(61,158,122,.15); } a { text-decoration: none; }";

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

<!-- HERO -->
<section style="background:linear-gradient(135deg,#1e293b 0%,#0f172a 100%);padding:60px 20px;position:relative;overflow:hidden;">
  <div style="position:absolute;top:0;left:0;right:0;bottom:0;background-image:radial-gradient(circle,rgba(255,255,255,.05) 1px,transparent 1px);background-size:24px 24px;"></div>
  <div style="max-width:1100px;margin:0 auto;position:relative;text-align:center;">
    <span style="display:inline-block;background:rgba(61,158,122,.2);color:#4ade80;padding:6px 16px;border-radius:99px;font-size:13px;font-weight:600;margin-bottom:20px;">Hubungi Kami</span>
    <h1 style="color:#fff;font-size:clamp(28px,5vw,48px);font-weight:800;line-height:1.2;margin:0 0 16px;">Kami Siap <span style="color:#4ade80;">Membantu Anda</span></h1>
    <p style="color:#94a3b8;font-size:clamp(14px,2vw,17px);max-width:550px;margin:0 auto;">Hubungi tim kami untuk informasi lebih lanjut atau daftarkan diri sebagai agen MonetraPay</p>
  </div>
</section>

<!-- MAIN CONTENT -->
<section style="padding:80px 20px;background:#f8fafc;">
  <div style="max-width:1100px;margin:0 auto;">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:40px;">

      <!-- FORM KONTAK -->
      <div style="background:#fff;border-radius:20px;padding:40px;box-shadow:0 1px 6px rgba(0,0,0,.07);">
        <h2 style="font-size:22px;font-weight:800;color:#1e293b;margin:0 0 8px;">Kirim Pesan</h2>
        <p style="color:#64748b;font-size:14px;margin:0 0 28px;">Isi form berikut dan tim kami akan menghubungi dalam 1x24 jam</p>

        <form action="/kontak" method="POST">
          @csrf
          <input type="hidden" name="_t" id="_form_time" value="0">
          <input type="text" name="website" style="display:none;" tabindex="-1" autocomplete="off">

          <div style="margin-bottom:16px;">
            <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">Nama Lengkap <span style="color:#ef4444;">*</span></label>
            <input type="text" name="name" required placeholder="Masukkan nama lengkap Anda" style="width:100%;padding:12px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:14px;outline:none;transition:border-color .2s;font-family:inherit;">
          </div>

          <div style="margin-bottom:16px;">
            <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">Nomor WhatsApp <span style="color:#ef4444;">*</span></label>
            <input type="tel" name="phone" required placeholder="Contoh: 08123456789" style="width:100%;padding:12px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:14px;outline:none;transition:border-color .2s;font-family:inherit;">
          </div>

          <div style="margin-bottom:16px;">
            <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">Email</label>
            <input type="email" name="email" placeholder="email@example.com" style="width:100%;padding:12px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:14px;outline:none;transition:border-color .2s;font-family:inherit;">
          </div>

          <div style="margin-bottom:16px;">
            <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">Kota / Kabupaten</label>
            <input type="text" name="city" placeholder="Kota domisili Anda" style="width:100%;padding:12px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:14px;outline:none;transition:border-color .2s;font-family:inherit;">
          </div>

          <div style="margin-bottom:24px;">
            <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">Pesan</label>
            <textarea name="message" rows="4" placeholder="Tulis pesan atau pertanyaan Anda di sini..." style="width:100%;padding:12px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:14px;outline:none;resize:vertical;transition:border-color .2s;font-family:inherit;"></textarea>
          </div>

          <button type="submit" style="width:100%;background:#3D9E7A;color:#fff;padding:14px 24px;border:none;border-radius:10px;font-size:16px;font-weight:700;cursor:pointer;font-family:inherit;">Kirim Pesan &rarr;</button>
        </form>

        <script>document.getElementById('_form_time') && (document.getElementById('_form_time').value = Math.floor(Date.now()/1000));</script>
      </div>

      <!-- INFO KONTAK -->
      <div>
        <div style="background:#fff;border-radius:20px;padding:36px;box-shadow:0 1px 6px rgba(0,0,0,.07);margin-bottom:24px;">
          <h3 style="font-size:18px;font-weight:700;color:#1e293b;margin:0 0 24px;">Informasi Kontak</h3>

          <div style="display:flex;align-items:flex-start;gap:14px;margin-bottom:20px;">
            <div style="width:40px;height:40px;background:#dcfce7;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#3D9E7A" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            </div>
            <div>
              <div style="font-size:12px;font-weight:600;color:#9ca3af;text-transform:uppercase;margin-bottom:2px;">Alamat</div>
              <div style="font-size:14px;color:#374151;line-height:1.5;">Jl. Gatot Subroto No. 123,<br>Jakarta Selatan 12930</div>
            </div>
          </div>

          <div style="display:flex;align-items:flex-start;gap:14px;margin-bottom:20px;">
            <div style="width:40px;height:40px;background:#dbeafe;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            </div>
            <div>
              <div style="font-size:12px;font-weight:600;color:#9ca3af;text-transform:uppercase;margin-bottom:2px;">Email</div>
              <div style="font-size:14px;color:#374151;"><a href="mailto:support@monetrapay.co.id" style="color:#2563eb;text-decoration:none;">support@monetrapay.co.id</a></div>
            </div>
          </div>

          <div style="display:flex;align-items:flex-start;gap:14px;margin-bottom:20px;">
            <div style="width:40px;height:40px;background:#fef9c3;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ca8a04" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13.6 19.79 19.79 0 0 1 1.61 5 2 2 0 0 1 3.6 3h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 10a16 16 0 0 0 6 6l.86-.86a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 17.16z"/></svg>
            </div>
            <div>
              <div style="font-size:12px;font-weight:600;color:#9ca3af;text-transform:uppercase;margin-bottom:2px;">Telepon</div>
              <div style="font-size:14px;color:#374151;"><a href="tel:+6281234567890" style="color:#374151;text-decoration:none;">+62 812-3456-7890</a></div>
            </div>
          </div>

          <div style="display:flex;align-items:flex-start;gap:14px;margin-bottom:20px;">
            <div style="width:40px;height:40px;background:#dcfce7;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="#25D366" stroke="none"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
            </div>
            <div>
              <div style="font-size:12px;font-weight:600;color:#9ca3af;text-transform:uppercase;margin-bottom:2px;">WhatsApp</div>
              <div style="font-size:14px;color:#374151;"><a href="https://wa.me/6281234567890" target="_blank" style="color:#16a34a;text-decoration:none;">+62 812-3456-7890</a></div>
            </div>
          </div>

          <div style="display:flex;align-items:flex-start;gap:14px;">
            <div style="width:40px;height:40px;background:#f1f5f9;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <div>
              <div style="font-size:12px;font-weight:600;color:#9ca3af;text-transform:uppercase;margin-bottom:2px;">Jam Operasional</div>
              <div style="font-size:14px;color:#374151;line-height:1.6;">Senin – Jumat: 08.00 – 17.00 WIB<br>Support WhatsApp 24/7</div>
            </div>
          </div>
        </div>

        <!-- Social Media -->
        <div style="background:#fff;border-radius:20px;padding:28px;box-shadow:0 1px 6px rgba(0,0,0,.07);">
          <h4 style="font-size:16px;font-weight:700;color:#1e293b;margin:0 0 16px;">Ikuti Kami</h4>
          <div style="display:flex;gap:12px;flex-wrap:wrap;">
            <a href="https://facebook.com/monetrapay" target="_blank" style="display:flex;align-items:center;gap:8px;background:#1877f2;color:#fff;padding:8px 14px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;">Facebook</a>
            <a href="https://instagram.com/monetrapay" target="_blank" style="display:flex;align-items:center;gap:8px;background:linear-gradient(135deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888);color:#fff;padding:8px 14px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;">Instagram</a>
            <a href="https://twitter.com/monetrapay" target="_blank" style="display:flex;align-items:center;gap:8px;background:#000;color:#fff;padding:8px 14px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;">X / Twitter</a>
            <a href="https://youtube.com/@monetrapay" target="_blank" style="display:flex;align-items:center;gap:8px;background:#ff0000;color:#fff;padding:8px 14px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;">YouTube</a>
            <a href="https://tiktok.com/@monetrapay" target="_blank" style="display:flex;align-items:center;gap:8px;background:#1e293b;color:#fff;padding:8px 14px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;">TikTok</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- FAQ SINGKAT -->
<section style="padding:80px 20px;background:#fff;">
  <div style="max-width:800px;margin:0 auto;">
    <div style="text-align:center;margin-bottom:48px;">
      <h2 style="font-size:clamp(22px,4vw,32px);font-weight:800;color:#1e293b;margin:0 0 12px;">Pertanyaan yang Sering Ditanyakan</h2>
      <p style="color:#64748b;font-size:15px;">Jawaban cepat untuk pertanyaan umum</p>
    </div>

    <div>
      <div style="border:1.5px solid #e2e8f0;border-radius:14px;padding:24px 28px;margin-bottom:12px;">
        <h4 style="font-size:16px;font-weight:700;color:#1e293b;margin:0 0 10px;">Bagaimana cara mendaftar jadi agen MonetraPay?</h4>
        <p style="color:#64748b;font-size:14px;line-height:1.7;margin:0;">Isi form di atas dengan data lengkap, tim kami akan menghubungi Anda dalam 1x24 jam untuk proses verifikasi dan aktivasi akun agen. Proses pendaftaran gratis tanpa biaya apapun.</p>
      </div>

      <div style="border:1.5px solid #e2e8f0;border-radius:14px;padding:24px 28px;margin-bottom:12px;">
        <h4 style="font-size:16px;font-weight:700;color:#1e293b;margin:0 0 10px;">Berapa minimal deposit untuk mulai bertransaksi?</h4>
        <p style="color:#64748b;font-size:14px;line-height:1.7;margin:0;">Minimal deposit adalah Rp 10.000. Anda bisa langsung bertransaksi setelah saldo masuk ke akun. Transfer bisa melalui bank transfer, e-wallet, atau QRIS.</p>
      </div>

      <div style="border:1.5px solid #e2e8f0;border-radius:14px;padding:24px 28px;">
        <h4 style="font-size:16px;font-weight:700;color:#1e293b;margin:0 0 10px;">Apa yang harus dilakukan jika transaksi gagal?</h4>
        <p style="color:#64748b;font-size:14px;line-height:1.7;margin:0;">Jika transaksi gagal, saldo akan dikembalikan otomatis dalam 1-5 menit. Jika tidak kembali dalam 10 menit, hubungi CS kami via WhatsApp di +62 812-3456-7890 dengan menyertakan ID transaksi.</p>
      </div>
    </div>
  </div>
</section>

</div>
HTML;
    }
}
