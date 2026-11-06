@php
    $siteName = \App\Models\Setting::get('site_name', 'general') ?? config('app.name');
    $siteUrl = config('app.url', 'https://monetrapay.com');
    $logoPath = \App\Models\Setting::get('logo', 'general');
    $logoUrl = $logoPath ? url(\Illuminate\Support\Facades\Storage::url($logoPath)) : null;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reset Password - {{$siteName}}</title>
</head>
<body style="margin:0;padding:0;font-family:Arial,Helvetica,sans-serif;background:#f0fdf4;">
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f0fdf4;padding:32px 16px;">
<tr><td align="center">
  <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;">

    {{-- HEADER --}}
    <tr>
      <td style="background:linear-gradient(135deg,#3D9E7A 0%,#2D7A5C 100%);border-radius:16px 16px 0 0;padding:32px 40px 28px;text-align:center;">
        @if($logoUrl)
          <img src="{{$logoUrl}}" alt="{{$siteName}}"
               style="max-height:48px;max-width:200px;width:auto;height:auto;display:inline-block;margin-bottom:8px;" />
          <br>
        @else
          <p style="margin:0 0 4px;font-size:28px;font-weight:900;color:#ffffff;letter-spacing:-0.5px;">{{$siteName}}</p>
        @endif
        <p style="margin:0;font-size:11px;color:#a7f3d0;font-weight:600;letter-spacing:2.5px;text-transform:uppercase;">Panel Administrasi</p>
      </td>
    </tr>

    {{-- BODY --}}
    <tr>
      <td style="background:#ffffff;padding:40px 40px 32px;">

        {{-- Icon --}}
        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:24px;">
          <tr><td align="center">
            <div style="width:72px;height:72px;background:#dcfce7;border-radius:50%;display:inline-block;line-height:72px;text-align:center;font-size:32px;">&#128272;</div>
          </td></tr>
        </table>

        <h1 style="margin:0 0 10px;font-size:24px;font-weight:900;color:#0f172a;text-align:center;">Reset Password Akun Anda</h1>
        <p style="margin:0 0 32px;font-size:15px;color:#475569;text-align:center;line-height:1.7;">Klik tombol di bawah untuk membuat password baru.<br><strong>Link berlaku 60 menit.</strong></p>

        {{-- CTA Button --}}
        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:32px;">
          <tr><td align="center">
            <a href="{{$url}}" style="display:inline-block;padding:14px 40px;background:linear-gradient(135deg,#3D9E7A,#2D7A5C);color:#fff;text-decoration:none;font-weight:700;border-radius:10px;font-size:15px;box-shadow:0 4px 15px rgba(61,158,122,0.4);letter-spacing:0.3px;">&#128275; Reset Password Sekarang</a>
          </td></tr>
        </table>

        {{-- Divider --}}
        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:24px;">
          <tr><td style="border-top:1px solid #e2e8f0;"></td></tr>
        </table>

        {{-- URL Fallback --}}
        <p style="margin:0 0 8px;font-size:12px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:1.5px;">Atau salin link berikut:</p>
        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;margin-bottom:24px;">
          <tr><td style="padding:14px 18px;">
            <p style="margin:0;font-size:12px;color:#64748b;word-break:break-all;font-family:'Courier New',Courier,monospace;line-height:1.6;">{{$url}}</p>
          </td></tr>
        </table>

        {{-- Warning Box --}}
        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#fffbeb;border:1px solid #fde68a;border-radius:10px;">
          <tr><td style="padding:14px 18px;">
            <p style="margin:0;font-size:13px;color:#92400e;line-height:1.7;">&#9888; <strong>Jika bukan Anda yang meminta reset password</strong>, abaikan email ini. Akun Anda tetap aman dan tidak ada perubahan yang akan dibuat.</p>
          </td></tr>
        </table>

      </td>
    </tr>

    {{-- FOOTER --}}
    <tr>
      <td style="background:#1e293b;border-radius:0 0 16px 16px;padding:28px 40px;text-align:center;">
        <p style="margin:0 0 6px;font-size:15px;font-weight:700;color:#ffffff;">{{$siteName}}</p>
        <p style="margin:0 0 10px;font-size:12px;color:#94a3b8;">Platform PPOB &amp; Payment Digital Indonesia</p>
        <p style="margin:0;font-size:11px;color:#475569;">Email ini dikirim otomatis &#8212; tidak perlu dibalas. &copy; {{date('Y')}} {{$siteName}}. All rights reserved.</p>
      </td>
    </tr>

  </table>
</td></tr>
</table>
</body>
</html>
