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
            $this->command->warn('No user found.');
            return;
        }

        Ad::truncate();

        $headerCode = '<div style="background:#1A5C38;color:white;text-align:center;padding:9px 16px;font-size:13px;font-weight:600;">Promo Spesial - Deposit mulai Rp10.000, margin pulsa naik 2x! <a href="/kontak" style="display:inline-block;margin-left:12px;padding:3px 14px;background:white;color:#1A5C38;border-radius:999px;font-size:11px;font-weight:700;text-decoration:none;">Daftar Sekarang</a></div>';

        $footerCode = '<div style="background:#0f172a;border-top:3px solid #27AE60;padding:28px 16px;text-align:center;"><div style="max-width:700px;margin:0 auto;"><p style="color:rgba(255,255,255,0.4);font-size:10px;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:10px;">IKLAN BERSPONSOR</p><h3 style="color:white;font-size:20px;font-weight:800;margin:0 0 8px;">Jadilah Distributor MonetraPay di Kota Anda</h3><p style="color:rgba(255,255,255,0.6);font-size:13px;margin:0 0 16px;">Dapatkan harga spesial, support dedikasi, dan potensi penghasilan tanpa batas.</p><a href="/kontak" style="display:inline-block;padding:10px 28px;background:#27AE60;color:white;border-radius:8px;font-weight:700;font-size:13px;text-decoration:none;">Hubungi Kami</a></div></div>';

        $sidebarCode = '<div style="border:2px solid #27AE60;border-radius:16px;overflow:hidden;"><div style="background:#27AE60;padding:16px;text-align:center;"><h4 style="color:white;font-size:18px;font-weight:800;margin:0;">Mulai Bisnis Pulsa</h4></div><div style="background:white;padding:16px;"><p style="font-size:13px;color:#374151;margin:0 0 12px;">Deposit mulai Rp10.000. 500+ produk. Transaksi 24/7.</p><a href="/kontak" style="display:block;padding:10px;background:#27AE60;color:white;border-radius:8px;font-weight:700;font-size:13px;text-decoration:none;text-align:center;">Daftar Gratis</a></div></div>';

        $inContentCode = '<div style="background:#1e293b;border-radius:16px;padding:20px;text-align:center;"><h4 style="color:white;font-size:15px;font-weight:800;margin:0 0 8px;">Pulsa All Operator Margin Terbaik</h4><p style="color:rgba(255,255,255,0.5);font-size:12px;margin:0 0 16px;">Telkomsel, Indosat, XL, Tri, Smartfren</p><a href="/layanan" style="display:block;padding:9px;background:#27AE60;color:white;border-radius:8px;font-weight:700;font-size:12px;text-decoration:none;">Lihat Harga</a></div>';

        $autoMidCode = '<div style="background:#f0fdf4;border:1px solid #bbf7d0;border-left:4px solid #16a34a;border-radius:12px;padding:16px 20px;margin:8px 0;font-family:inherit;"><p style="color:#166534;font-size:11px;text-transform:uppercase;letter-spacing:0.08em;font-weight:700;margin:0 0 8px;">Iklan</p><div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;"><div style="flex:1;min-width:160px;"><p style="color:#15803d;font-size:15px;font-weight:800;margin:0 0 4px;">Pulsa Lebih Untung Bersama MonetraPay</p><p style="color:#166534;font-size:12px;margin:0;opacity:0.8;">500+ produk digital, transaksi real-time 24/7</p></div><a href="/kontak" style="display:inline-block;padding:8px 20px;background:#16a34a;color:white;border-radius:8px;font-weight:700;font-size:12px;text-decoration:none;white-space:nowrap;">Daftar Agen</a></div></div>';

        $autoBottomCode = '<div style="background:#fff7ed;border:1px solid #fed7aa;border-left:4px solid #ea580c;border-radius:12px;padding:16px 20px;margin:8px 0;font-family:inherit;"><p style="color:#7c2d12;font-size:11px;text-transform:uppercase;letter-spacing:0.08em;font-weight:700;margin:0 0 8px;">Promo Terbatas</p><div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;"><div style="flex:1;min-width:160px;"><p style="color:#c2410c;font-size:15px;font-weight:800;margin:0 0 4px;">Top-Up Game Harga Terbaik</p><p style="color:#7c2d12;font-size:12px;margin:0;opacity:0.8;">Mobile Legends, PUBG, Free Fire dan 100+ game lainnya</p></div><a href="/layanan" style="display:inline-block;padding:8px 20px;background:#ea580c;color:white;border-radius:8px;font-weight:700;font-size:12px;text-decoration:none;white-space:nowrap;">Lihat Harga</a></div></div>';

        $ads = [
            ['user_id' => $admin->id, 'name' => 'Promo Header Bar', 'type' => 'manual', 'position' => 'header', 'is_active' => true, 'code' => $headerCode],
            ['user_id' => $admin->id, 'name' => 'Banner Footer CTA', 'type' => 'manual', 'position' => 'footer', 'is_active' => true, 'code' => $footerCode],
            ['user_id' => $admin->id, 'name' => 'Sidebar Daftar Agen', 'type' => 'manual', 'position' => 'sidebar', 'is_active' => true, 'code' => $sidebarCode],
            ['user_id' => $admin->id, 'name' => 'In-Content Promo Pulsa', 'type' => 'manual', 'position' => 'in_content', 'is_active' => true, 'code' => $inContentCode],
            ['user_id' => $admin->id, 'name' => 'Auto-Insert setiap 3 paragraf', 'type' => 'manual', 'position' => 'auto_insert', 'is_active' => true, 'interval' => 3, 'code' => $autoMidCode],
            ['user_id' => $admin->id, 'name' => 'Auto-Insert setiap 6 paragraf', 'type' => 'manual', 'position' => 'auto_insert', 'is_active' => true, 'interval' => 6, 'code' => $autoBottomCode],
        ];

        foreach ($ads as $ad) {
            Ad::create($ad);
        }

        $this->command->info('Ads seeded: ' . count($ads) . ' iklan di 5 posisi');
        $this->command->info('  header, footer, sidebar, in_content, auto_insert (x2)');
    }
}