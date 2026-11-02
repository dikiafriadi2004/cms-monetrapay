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
<title>Balasan dari {{ $siteName }}</title>
</head>
<body style="margin:0;padding:0;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;background:#f0fdf4;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f0fdf4;padding:40px 20px;">
<tr><td align="center">
  <table width="100%" cellpadding="0" cellspacing="0" style="max-width:580px;">

    <tr>
      <td align="center" style="padding-bottom:24px;">
        <span style="font-size:24px;font-weight:900;color:#3D9E7A;">{{ $siteName }}</span>
      </td>
    </tr>

    <tr>
      <td style="background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.06);">

        <table width="100%" cellpadding="0" cellspacing="0">
          <tr><td style="background:linear-gradient(90deg,#3D9E7A,#2D7A5C);height:5px;"></td></tr>
        </table>

        <table width="100%" cellpadding="0" cellspacing="0">
          <tr><td style="padding:36px 36px 28px;">

            <p style="margin:0 0 6px;font-size:12px;font-weight:700;color:#3D9E7A;text-transform:uppercase;letter-spacing:1.5px;">Balasan dari Tim</p>
            <h1 style="margin:0 0 6px;font-size:21px;font-weight:800;color:#0f172a;">{{ $siteName }}</h1>
            <p style="margin:0 0 28px;font-size:14px;color:#64748b;">Halo <strong>{{ $contact->name }}</strong>, berikut balasan untuk pesan Anda.</p>

            <hr style="border:none;border-top:1px solid #f1f5f9;margin:0 0 24px;">

            {{-- Original message quote --}}
            @if($contact->message)
            <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc;border-left:3px solid #e2e8f0;border-radius:0 8px 8px 0;margin-bottom:24px;">
              <tr><td style="padding:14px 18px;">
                <p style="margin:0 0 6px;font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;">Pesan Anda sebelumnya</p>
                <p style="margin:0;font-size:13px;color:#64748b;line-height:1.7;white-space:pre-line;font-style:italic;">{{ $contact->message }}</p>
              </td></tr>
            </table>
            @endif

            {{-- Reply --}}
            <table width="100%" cellpadding="0" cellspacing="0" style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;margin-bottom:28px;">
              <tr><td style="padding:22px 24px;">
                <p style="margin:0 0 10px;font-size:11px;font-weight:700;color:#2D7A5C;text-transform:uppercase;letter-spacing:1px;">Balasan dari {{ $adminName }}</p>
                <p style="margin:0;font-size:15px;color:#0f172a;line-height:1.8;white-space:pre-line;">{{ $replyMessage }}</p>
              </td></tr>
            </table>

            <p style="margin:0 0 24px;font-size:13px;color:#64748b;text-align:center;line-height:1.6;">
              Balas email ini jika ada pertanyaan lanjutan.
            </p>

          </td></tr>
        </table>

        <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc;border-top:1px solid #f1f5f9;">
          <tr><td style="padding:18px 36px;">
            <p style="margin:0;font-size:13px;color:#64748b;line-height:1.8;">
              <strong style="color:#0f172a;">{{ $siteName }}</strong><br>
              @if($adminEmail)✉ <a href="mailto:{{ $adminEmail }}" style="color:#3D9E7A;text-decoration:none;">{{ $adminEmail }}</a><br>@endif
              @if($adminPhone)📞 {{ $adminPhone }}@endif
            </p>
          </td></tr>
        </table>

      </td>
    </tr>

    <tr>
      <td align="center" style="padding-top:20px;">
        <p style="margin:0;font-size:11px;color:#94a3b8;">Ini adalah balasan dari pesan yang Anda kirimkan ke {{ $siteName }}.</p>
      </td>
    </tr>

  </table>
</td></tr>
</table>
</body>
</html>
