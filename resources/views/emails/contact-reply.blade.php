@php
    $siteName = \App\Models\Setting::get('site_name', 'general') ?? config('app.name');
    $adminEmail = \App\Models\Setting::get('contact_email', 'general');
    $adminPhone = \App\Models\Setting::get('contact_phone', 'general');
    $siteUrl = config('app.url', 'https://monetrapay.com');
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Balasan dari {{$siteName}}</title>
</head>
<body style="margin:0;padding:0;font-family:Arial,Helvetica,sans-serif;background:#f0fdf4;">
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f0fdf4;padding:32px 16px;">
<tr><td align="center">
  <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;">

    {{-- HEADER --}}
    <tr>
      <td style="background:linear-gradient(135deg,#3D9E7A 0%,#2D7A5C 100%);border-radius:16px 16px 0 0;padding:36px 40px 32px;text-align:center;">
        <p style="margin:0 0 4px;font-size:28px;font-weight:900;color:#ffffff;letter-spacing:-0.5px;">{{$siteName}}</p>
        <p style="margin:0;font-size:12px;color:#a7f3d0;font-weight:600;letter-spacing:2.5px;text-transform:uppercase;">Balasan Pesan</p>
      </td>
    </tr>

    {{-- BODY --}}
    <tr>
      <td style="background:#ffffff;padding:40px 40px 32px;">

        {{-- Icon --}}
        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:24px;">
          <tr><td align="center">
            <div style="width:72px;height:72px;background:#dcfce7;border-radius:50%;display:inline-block;line-height:72px;text-align:center;font-size:32px;">&#128172;</div>
          </td></tr>
        </table>

        <h1 style="margin:0 0 8px;font-size:22px;font-weight:900;color:#0f172a;text-align:center;">Balasan dari Tim {{$siteName}}</h1>
        <p style="margin:0 0 28px;font-size:15px;color:#475569;text-align:center;line-height:1.7;">Halo <strong>{{$contact->name}}</strong>, berikut adalah balasan atas pesan yang Anda kirimkan.</p>

        {{-- Original Message Quote --}}
        @if($contact->message)
        <p style="margin:0 0 8px;font-size:12px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:1px;">Pesan Anda sebelumnya:</p>
        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f8fafc;border-left:4px solid #e2e8f0;border-radius:0 10px 10px 0;margin-bottom:20px;">
          <tr><td style="padding:16px 20px;">
            <p style="margin:0;font-size:13px;color:#64748b;line-height:1.7;white-space:pre-line;font-style:italic;">{{$contact->message}}</p>
          </td></tr>
        </table>
        @endif

        {{-- Arrow Divider --}}
        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:20px;">
          <tr>
            <td style="border-top:1px solid #e2e8f0;"></td>
            <td style="padding:0 12px;font-size:18px;color:#3D9E7A;white-space:nowrap;">&#11015; Balasan:</td>
            <td style="border-top:1px solid #e2e8f0;"></td>
          </tr>
        </table>

        {{-- Reply Message --}}
        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#ffffff;border:1px solid #bbf7d0;border-left:5px solid #3D9E7A;border-radius:0 12px 12px 0;margin-bottom:28px;">
          <tr><td style="padding:20px 24px;">
            <p style="margin:0 0 8px;font-size:11px;font-weight:700;color:#2D7A5C;text-transform:uppercase;letter-spacing:1px;">Dibalas oleh {{$adminName}}</p>
            <p style="margin:0;font-size:15px;color:#0f172a;line-height:1.8;white-space:pre-line;">{{$replyMessage}}</p>
          </td></tr>
        </table>

        <p style="margin:0 0 28px;font-size:14px;color:#64748b;text-align:center;line-height:1.7;">Terima kasih telah menghubungi kami.<br>Balas email ini jika ada pertanyaan lanjutan.</p>

        {{-- CTA --}}
        <table width="100%" cellpadding="0" cellspacing="0" border="0">
          <tr><td align="center">
            <a href="{{$siteUrl}}" style="display:inline-block;padding:14px 36px;background:linear-gradient(135deg,#3D9E7A,#2D7A5C);color:#fff;text-decoration:none;font-weight:700;border-radius:10px;font-size:15px;box-shadow:0 4px 15px rgba(61,158,122,0.4);letter-spacing:0.3px;">Kunjungi Website</a>
          </td></tr>
        </table>

      </td>
    </tr>

    {{-- FOOTER --}}
    <tr>
      <td style="background:#1e293b;border-radius:0 0 16px 16px;padding:28px 40px;text-align:center;">
        <p style="margin:0 0 6px;font-size:15px;font-weight:700;color:#ffffff;">{{$siteName}}</p>
        <p style="margin:0 0 8px;font-size:12px;color:#94a3b8;">Platform PPOB &amp; Payment Digital Indonesia</p>
        @if($adminEmail)<p style="margin:0 0 4px;font-size:12px;color:#64748b;">&#128231; <a href="mailto:{{$adminEmail}}" style="color:#6ee7b7;text-decoration:none;">{{$adminEmail}}</a></p>@endif
        @if($adminPhone)<p style="margin:0 0 8px;font-size:12px;color:#64748b;">&#128222; {{$adminPhone}}</p>@endif
        <p style="margin:0;font-size:11px;color:#475569;">Ini adalah balasan atas pesan yang Anda kirimkan ke {{$siteName}}. &copy; {{date('Y')}} {{$siteName}}.</p>
      </td>
    </tr>

  </table>
</td></tr>
</table>
</body>
</html>
