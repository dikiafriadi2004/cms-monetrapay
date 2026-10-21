<?php

namespace Database\Seeders;

use App\Models\Ad;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdsSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first();
        if (! $admin) {
            $this->command->warn('No user found. Run RolePermissionSeeder first.');
            return;
        }

        Ad::truncate();

        $headerCode = <<<'HTML'
<div style="background:#1A5C38;color:white;text-align:center;padding:9px 16px;font-size:13px;font-weight:600;letter-spacing:0.02em;">
    Promo Spesial — Deposit mulai Rp10.000, margin pulsa naik 2x!
    <a href="/kontak" style="display:inline-block;margin-left:12px;padding:3px 14px;background:white;color:#1A5C38;border-radius:999px;font-size:11px;font-weight:700;text-decoration:none;">Daftar Sekarang</a>
</div>
HTML;

        $footerCode = <<<'HTML'
<div style="background:#0f172a;border-top:3px solid #27AE60;padding:28px 16px;text-align:center;">
    <div style="max-width:700px;margin:0 auto;">
        <p style="color:rgba(255,255,255,0.4);font-size:10px;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:10px;">IKLAN BERSPONSOR</p>
        <h3 style="color:white;font-size:20px;font-weight:800;margin:0 0 8px;">Jadilah Distributor MonetraPay di Kota Anda</h3>
        <p style="color:rgba(255,255,255,0.6);font-size:13px;margin:0 0 16px;">Dapatkan harga spesial, support dedikasi, dan potensi penghasilan tanpa batas.</p>
        <a href="/kontak" style="display:inline-block;padding:10px 28px;background:#27AE60;color:white;border-radius:8px;font-weight:700;font-size:13px;text-decoration:none;">Hubungi Kami Sekarang</a>
    </div>
</div>
HTML;

        $sidebarCode = <<<'HTML'
<div style="border:2px solid #27AE60;border-radius:16px;overflow:hidden;font-family:inherit;">
    <div style="background:#27AE60;padding:16px;text-align:center;">
        <p style="color:rgba(255,255,255,0.75);font-size:10px;text-transform:uppercase;letter-spacing:0.1em;margin:0 0 6px;">IKLAN</p>
        <h4 style="color:white;font-size:18px;font-weight:800;margin:0;line-height:1.25;">Mulai Bisnis<br>Pulsa Hari Ini</h4>
    </div>
    <div style="background:white;padding:16px;">
        <ul style="list-style:none;padding:0;margin:0 0 16px;">
            <li style="display:flex;align-items:center;gap:8px;padding:4px 0;font-size:13px;color:#374151;"><span style="color:#27AE60;font-weight:700;">v</span> Deposit mulai Rp10.000</li>
            <li style="display:flex;align-items:center;gap:8px;padding:4px 0;font-size:13px;color:#374151;"><span style="color:#27AE60;font-weight:700;">v</span> 500+ produk digital</li>
            <li style="display:flex;align-items:center;gap:8px;padding:4px 0;font-size:13px;color:#374151;"><span style="color:#27AE60;font-weight:700;">v</span> Transaksi 24/7 real-time</li>
            <li style="display:flex;align-items:center;gap:8px;padding:4px 0;font-size:13px;color:#374151;"><span style="color:#27AE60;font-weight:700;">v</span> Support tim profesional</li>
        </ul>
        <a href="/kontak" style="display:block;padding:10px;background:#27AE60;color:white;border-radius:8px;font-weight:700;font-size:13px;text-decoration:none;text-align:center;">Daftar Gratis</a>
    </div>
</div>
HTML;

        $inContentCode = <<<'HTML'
<div style="background:#1e293b;border-radius:16px;padding:20px;text-align:center;font-family:inherit;">
    <p style="color:rgba(255,255,255,0.4);font-size:10px;text-transform:uppercase;letter-spacing:0.1em;margin:0 0 12px;">PROMO TERBATAS</p>
    <h4 style="color:white;font-size:15px;font-weight:800;margin:0 0 8px;line-height:1.35;">Pulsa All Operator<br><span style="color:#27AE60;">Margin Terbaik</span></h4>
    <p style="color:rgba(255,255,255,0.5);font-size:12px;margin:0 0 16px;line-height:1.5;">Telkomsel, Indosat, XL, Tri, Smartfren</p>
    <a href="/layanan" style="display:block;padding:9px;background:#27AE60;color:white;border-radius:8px;font-weight:700;font-size:12px;text-decoration:none;">Lihat Harga</a>
</div>
HTML;

        $ads = [
            [
                'user_id'   => $admin->id,
                'name'      => 'Promo Header Bar',
                'type'      => 'manual',
                'position'  => 'header',
                'is_active' => true,
                'code'      => $headerCode,
            ],
            [
                'user_id'   => $admin->id,
                'name'      => 'Banner Footer CTA',
                'type'      => 'manual',
                'position'  => 'footer',
                'is_active' => true,
                'code'      => $footerCode,
            ],
            [
                'user_id'   => $admin->id,
                'name'      => 'Sidebar - Daftar Agen',
                'type'      => 'manual',
                'position'  => 'sidebar',
                'is_active' => true,
                'code'      => $sidebarCode,
            ],
            [
                'user_id'   => $admin->id,
                'name'      => 'In-Content Promo Pulsa',
                'type'      => 'manual',
                'position'  => 'in_content',
                'is_active' => true,
                'code'      => $inContentCode,
            ],
        ];

        foreach ($ads as $ad) {
            Ad::create($ad);
        }

        $this->command->info('Ads seeded: ' . count($ads) . ' iklan');
        $this->command->info('  Posisi: header, footer, sidebar, in_content (alias sidebar_bottom)');
    }
}
