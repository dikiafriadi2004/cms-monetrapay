@php
    $siteName = \App\Models\Setting::get('site_name', 'general') ?? config('app.name');
    $siteUrl = config('app.url', 'https://monetrapay.com');
    $unsubToken = $subscriber->confirmation_token ?? '';
    $unsubUrl = $unsubscribeUrl ?? ($unsubToken ? url(route('newsletter.unsubscribe', $unsubToken, false)) : '#');
    $logoPath = \App\Models\Setting::get('logo', 'general');
    $logoUrl = $logoPath ? url(\Illuminate\Support\Facades\Storage::url($logoPath)) : null;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{$campaign->subject}} - {{$siteName}}</title>
</head>
<body style="margin:0;padding:0;font-family:Arial,Helvetica,sans-serif;background:#f0fdf4;">
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f0fdf4;padding:32px 16px;">
<tr><td align="center">
  <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;">

    {{-- HEADER (large, branded) --}}
    <tr>
      <td style="background:linear-gradient(135deg,#3D9E7A 0%,#2D7A5C 100%);border-radius:16px 16px 0 0;padding:44px 40px 36px;text-align:center;">
        <p style="margin:0 0 6px;font-size:12px;font-weight:700;color:#a7f3d0;text-transform:uppercase;letter-spacing:3px;">Newsletter</p>
        @if($logoUrl)
          <img src="{{$logoUrl}}" alt="{{$siteName}}"
               style="max-height:56px;max-width:220px;width:auto;height:auto;display:inline-block;margin-bottom:8px;" />
          <br>
        @else
          <p style="margin:0 0 8px;font-size:34px;font-weight:900;color:#ffffff;letter-spacing:-1px;">{{$siteName}}</p>
        @endif
        <p style="margin:0;font-size:14px;color:#d1fae5;">Platform PPOB &amp; Payment Digital Indonesia</p>
        @if($subscriber->name ?? false)
        <p style="margin:16px 0 0;font-size:13px;color:#a7f3d0;">Halo, <strong style="color:#ffffff;">{{$subscriber->name}}</strong>!</p>
        @endif
      </td>
    </tr>

    {{-- Gradient Divider --}}
    <tr>
      <td style="background:linear-gradient(90deg,#2D7A5C,#F59E0B,#2D7A5C);height:4px;"></td>
    </tr>

    {{-- BODY --}}
    <tr>
      <td style="background:#ffffff;padding:40px 40px 32px;">

        {{-- Subject as H1 --}}
        <h1 style="margin:0 0 24px;font-size:26px;font-weight:900;color:#0f172a;line-height:1.3;">{{$campaign->subject}}</h1>

        {{-- Divider --}}
        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:24px;">
          <tr><td style="border-top:2px solid #3D9E7A;width:60px;"></td><td style="border-top:1px solid #e2e8f0;"></td></tr>
        </table>

        {{-- Content --}}
        <div style="font-size:15px;color:#334155;line-height:1.8;">
          {!! $campaign->content !!}
        </div>

        {{-- Divider --}}
        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:32px 0;">
          <tr><td style="border-top:1px solid #e2e8f0;"></td></tr>
        </table>

        {{-- CTA Section --}}
        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);border-radius:12px;border:1px solid #bbf7d0;margin-bottom:8px;">
          <tr><td style="padding:28px 32px;text-align:center;">
            <p style="margin:0 0 6px;font-size:12px;font-weight:700;color:#2D7A5C;text-transform:uppercase;letter-spacing:1.5px;">Bergabunglah Bersama Kami</p>
            <p style="margin:0 0 20px;font-size:17px;font-weight:700;color:#0f172a;">Tertarik Bergabung Sebagai Agen?</p>
            <p style="margin:0 0 24px;font-size:14px;color:#475569;line-height:1.6;">Mulai bisnis PPOB Anda hari ini dan raih penghasilan tambahan bersama {{$siteName}}.</p>
            <a href="{{$siteUrl}}" style="display:inline-block;padding:14px 36px;background:linear-gradient(135deg,#3D9E7A,#2D7A5C);color:#fff;text-decoration:none;font-weight:700;border-radius:10px;font-size:15px;box-shadow:0 4px 15px rgba(61,158,122,0.4);letter-spacing:0.3px;">&#128640; Daftar Sekarang</a>
          </td></tr>
        </table>

      </td>
    </tr>

    {{-- FOOTER --}}
    <tr>
      <td style="background:#1e293b;border-radius:0 0 16px 16px;padding:28px 40px;text-align:center;">
        <p style="margin:0 0 6px;font-size:15px;font-weight:700;color:#ffffff;">{{$siteName}}</p>
        <p style="margin:0 0 12px;font-size:12px;color:#94a3b8;">Platform PPOB &amp; Payment Digital Indonesia<br>Jl. Contoh No. 123, Jakarta, Indonesia</p>
        <p style="margin:0 0 12px;font-size:12px;color:#64748b;">Anda menerima email ini karena berlangganan newsletter <strong style="color:#94a3b8;">{{$siteName}}</strong>.</p>
        <a href="{{$unsubUrl}}" style="font-size:12px;color:#6ee7b7;text-decoration:underline;">Berhenti berlangganan (Unsubscribe)</a>
        <p style="margin:16px 0 0;font-size:11px;color:#475569;">&copy; {{date('Y')}} {{$siteName}}. All rights reserved.</p>
      </td>
    </tr>

  </table>
</td></tr>
</table>
</body>
</html>
