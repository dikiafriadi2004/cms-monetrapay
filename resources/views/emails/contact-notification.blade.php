@php
    $siteName = \App\Models\Setting::get('site_name', 'general', 'MonetraPay');
    $adminUrl = url('/admin/contacts/' . $submission->id);
    $waNumber = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $submission->phone));
    $siteUrl = config('app.url', 'https://monetrapay.com');
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pendaftaran Baru</title>
</head>
<body style="margin:0;padding:0;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;background:#fff7ed;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#fff7ed;padding:40px 20px;">
<tr><td align="center">
  <table width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;">

    {{-- Header --}}
    <tr>
      <td align="center" style="padding-bottom:24px;">
        <span style="font-size:24px;font-weight:900;color:#FE8134;letter-spacing:-0.5px;">{{ $siteName }}</span>
        <p style="margin:4px 0 0;font-size:12px;color:#9a3412;font-weight:600;text-transform:uppercase;letter-spacing:2px;">Notifikasi Admin</p>
      </td>
    </tr>

    {{-- Card --}}
    <tr>
      <td style="background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.06);">

        <table width="100%" cellpadding="0" cellspacing="0">
          <tr><td style="background:linear-gradient(90deg,#FE8134,#E06B1F);height:5px;"></td></tr>
        </table>

        <table width="100%" cellpadding="0" cellspacing="0">
          <tr><td style="padding:32px 36px 28px;">

            {{-- Badge --}}
            <table cellpadding="0" cellspacing="0" style="margin-bottom:20px;">
              <tr>
                <td style="background:#fff7ed;border:1px solid #fed7aa;border-radius:20px;padding:6px 14px;font-size:12px;font-weight:700;color:#c2410c;">🔔 Pendaftaran Baru</td>
              </tr>
            </table>

            <h1 style="margin:0 0 4px;font-size:22px;font-weight:800;color:#0f172a;">{{ $submission->name }}</h1>
            <p style="margin:0 0 24px;font-size:13px;color:#94a3b8;">{{ $submission->created_at->translatedFormat('d F Y, H:i') }}</p>

            <hr style="border:none;border-top:1px solid #f1f5f9;margin:0 0 24px;">

            {{-- Detail table --}}
            <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc;border-radius:12px;margin-bottom:24px;">
              <tr><td style="padding:0;">
                <table width="100%" cellpadding="0" cellspacing="0">
                  <tr style="border-bottom:1px solid #f1f5f9;">
                    <td style="padding:12px 20px;font-size:13px;color:#64748b;width:35%;">Nama</td>
                    <td style="padding:12px 20px;font-size:14px;font-weight:700;color:#0f172a;">{{ $submission->name }}</td>
                  </tr>
                  <tr style="border-bottom:1px solid #f1f5f9;">
                    <td style="padding:12px 20px;font-size:13px;color:#64748b;">WhatsApp</td>
                    <td style="padding:12px 20px;">
                      <a href="https://wa.me/{{ $waNumber }}" style="font-size:14px;font-weight:700;color:#16a34a;text-decoration:none;">{{ $submission->phone }} 💬</a>
                    </td>
                  </tr>
                  @if($submission->email)
                  <tr style="border-bottom:1px solid #f1f5f9;">
                    <td style="padding:12px 20px;font-size:13px;color:#64748b;">Email</td>
                    <td style="padding:12px 20px;">
                      <a href="mailto:{{ $submission->email }}" style="font-size:14px;color:#3D9E7A;text-decoration:none;">{{ $submission->email }}</a>
                    </td>
                  </tr>
                  @endif
                  @if($submission->city)
                  <tr style="border-bottom:1px solid #f1f5f9;">
                    <td style="padding:12px 20px;font-size:13px;color:#64748b;">Kota</td>
                    <td style="padding:12px 20px;font-size:14px;color:#0f172a;">{{ $submission->city }}</td>
                  </tr>
                  @endif
                  <tr>
                    <td style="padding:12px 20px;font-size:13px;color:#64748b;">IP</td>
                    <td style="padding:12px 20px;font-size:12px;color:#94a3b8;font-family:monospace;">{{ $submission->ip_address ?? '—' }}</td>
                  </tr>
                </table>
              </td></tr>
            </table>

            @if($submission->message)
            <table width="100%" cellpadding="0" cellspacing="0" style="border-left:4px solid #FE8134;background:#fff7ed;border-radius:0 8px 8px 0;margin-bottom:24px;">
              <tr><td style="padding:14px 18px;">
                <p style="margin:0 0 6px;font-size:11px;font-weight:700;color:#c2410c;text-transform:uppercase;letter-spacing:1px;">Pesan</p>
                <p style="margin:0;font-size:14px;color:#431407;line-height:1.7;white-space:pre-line;">{{ $submission->message }}</p>
              </td></tr>
            </table>
            @endif

            {{-- CTA Buttons --}}
            <table width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td align="center" style="padding-top:8px;">
                  <a href="{{ $adminUrl }}" style="display:inline-block;padding:12px 24px;background:#3D9E7A;color:#ffffff;text-decoration:none;font-weight:700;border-radius:10px;font-size:14px;margin-right:8px;">📋 Buka Admin Panel</a>
                  <a href="https://wa.me/{{ $waNumber }}" style="display:inline-block;padding:12px 24px;background:#25D366;color:#ffffff;text-decoration:none;font-weight:700;border-radius:10px;font-size:14px;">💬 WhatsApp</a>
                </td>
              </tr>
            </table>

            @if($submission->email)
            <p style="margin:20px 0 0;font-size:12px;color:#94a3b8;text-align:center;">Tekan <strong>Reply</strong> pada email ini untuk membalas langsung ke <strong>{{ $submission->email }}</strong></p>
            @endif

          </td></tr>
        </table>

        <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc;border-top:1px solid #f1f5f9;">
          <tr><td style="padding:14px 36px;text-align:center;">
            <p style="margin:0;font-size:11px;color:#94a3b8;">{{ $siteName }} · Submission #{{ $submission->id }}</p>
          </td></tr>
        </table>

      </td>
    </tr>

  </table>
</td></tr>
</table>
</body>
</html>
