@php
    $siteName = \App\Models\Setting::get('site_name', 'general') ?? config('app.name');
    $siteUrl = config('app.url', 'https://monetrapay.com');
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reset Password</title>
</head>
<body style="margin:0;padding:0;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;background:#f0fdf4;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f0fdf4;padding:40px 20px;">
<tr><td align="center">
  <table width="100%" cellpadding="0" cellspacing="0" style="max-width:540px;">

    <tr>
      <td align="center" style="padding-bottom:24px;">
        <span style="font-size:24px;font-weight:900;color:#3D9E7A;">{{ $siteName }}</span>
        <p style="margin:4px 0 0;font-size:12px;color:#4b7c65;font-weight:600;">Panel Administrasi</p>
      </td>
    </tr>

    <tr>
      <td style="background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.06);">

        <table width="100%" cellpadding="0" cellspacing="0">
          <tr><td style="background:linear-gradient(90deg,#3D9E7A,#2D7A5C);height:5px;"></td></tr>
        </table>

        <table width="100%" cellpadding="0" cellspacing="0">
          <tr><td style="padding:40px 36px 32px;text-align:center;">

            {{-- Lock icon --}}
            <div style="width:64px;height:64px;background:#dcfce7;border-radius:50%;display:inline-block;line-height:64px;text-align:center;font-size:28px;margin-bottom:20px;">🔐</div>

            <h1 style="margin:0 0 10px;font-size:22px;font-weight:800;color:#0f172a;">Reset Password</h1>
            <p style="margin:0 0 32px;font-size:14px;color:#64748b;line-height:1.7;max-width:380px;display:inline-block;">
              Kami menerima permintaan untuk mereset password akun CMS Anda. Klik tombol di bawah untuk membuat password baru.
            </p>

            {{-- CTA Button --}}
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:28px;">
              <tr>
                <td align="center">
                  <a href="{{ $url }}"
                     style="display:inline-block;padding:16px 40px;background:#3D9E7A;color:#ffffff;text-decoration:none;font-weight:700;border-radius:12px;font-size:15px;letter-spacing:0.3px;box-shadow:0 4px 12px rgba(61,158,122,0.3);">
                    Reset Password Sekarang
                  </a>
                </td>
              </tr>
            </table>

            {{-- URL fallback --}}
            <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc;border-radius:10px;margin-bottom:24px;border:1px solid #e2e8f0;">
              <tr><td style="padding:14px 18px;">
                <p style="margin:0 0 6px;font-size:11px;color:#94a3b8;font-weight:700;text-transform:uppercase;letter-spacing:1px;">Atau copy link ini:</p>
                <p style="margin:0;font-size:11px;color:#64748b;word-break:break-all;font-family:monospace;line-height:1.5;">{{ $url }}</p>
              </td></tr>
            </table>

            {{-- Warning --}}
            <table width="100%" cellpadding="0" cellspacing="0" style="background:#fffbeb;border:1px solid #fde68a;border-radius:10px;">
              <tr><td style="padding:14px 18px;">
                <p style="margin:0;font-size:12px;color:#92400e;line-height:1.6;">
                  ⚠ <strong>Link berlaku 60 menit.</strong> Jika Anda tidak meminta reset password, abaikan email ini — akun Anda tetap aman dan tidak ada perubahan yang dibuat.
                </p>
              </td></tr>
            </table>

          </td></tr>
        </table>

        <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc;border-top:1px solid #f1f5f9;">
          <tr><td style="padding:14px 36px;text-align:center;">
            <p style="margin:0;font-size:11px;color:#94a3b8;">© {{ date('Y') }} {{ $siteName }} CMS. Email ini dikirim otomatis.</p>
          </td></tr>
        </table>

      </td>
    </tr>

  </table>
</td></tr>
</table>
</body>
</html>
