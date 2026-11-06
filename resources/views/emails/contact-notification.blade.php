@php
    $siteName = \App\Models\Setting::get('site_name', 'general') ?? config('app.name');
    $adminUrl = url('/admin/contacts/' . $submission->id);
    $waNumber = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $submission->phone));
    $siteUrl = config('app.url', 'https://monetrapay.com');
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>&#128276; Pendaftaran Baru - {{$siteName}}</title>
</head>
<body style="margin:0;padding:0;font-family:Arial,Helvetica,sans-serif;background:#0f172a;">
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#0f172a;padding:32px 16px;">
<tr><td align="center">
  <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;">

    {{-- HEADER (dark admin style) --}}
    <tr>
      <td style="background:linear-gradient(135deg,#0f3460 0%,#1a5276 100%);border-radius:16px 16px 0 0;padding:36px 40px 28px;text-align:center;">
        <p style="margin:0 0 8px;font-size:13px;font-weight:700;color:#93c5fd;text-transform:uppercase;letter-spacing:3px;">&#128276; Notifikasi Admin</p>
        <p style="margin:0 0 4px;font-size:28px;font-weight:900;color:#ffffff;letter-spacing:-0.5px;">{{$siteName}}</p>
        <p style="margin:0;font-size:13px;color:#7dd3fc;">Pendaftaran Baru Masuk</p>
      </td>
    </tr>

    {{-- ACTION REQUIRED Badge --}}
    <tr>
      <td style="background:#1e293b;padding:14px 40px;">
        <table cellpadding="0" cellspacing="0" border="0">
          <tr>
            <td style="background:#dc2626;border-radius:6px;padding:6px 14px;font-size:11px;font-weight:900;color:#fff;letter-spacing:2px;text-transform:uppercase;">&#9888; ACTION REQUIRED</td>
            <td style="padding-left:12px;font-size:13px;color:#94a3b8;">Tindak lanjuti segera</td>
          </tr>
        </table>
      </td>
    </tr>

    {{-- BODY --}}
    <tr>
      <td style="background:#ffffff;padding:32px 40px;">

        <h1 style="margin:0 0 4px;font-size:22px;font-weight:900;color:#0f172a;">{{$submission->name}}</h1>
        <p style="margin:0 0 24px;font-size:13px;color:#94a3b8;">Diterima: {{$submission->created_at->translatedFormat('d F Y, H:i')}} WIB</p>

        {{-- Info Card --}}
        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f8fafc;border-radius:12px;border:1px solid #e2e8f0;margin-bottom:24px;">
          <tr><td style="padding:0;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
              <tr style="border-bottom:1px solid #f1f5f9;">
                <td style="padding:14px 20px;font-size:12px;font-weight:700;color:#94a3b8;text-transform:uppercase;width:35%;border-bottom:1px solid #f1f5f9;">Nama</td>
                <td style="padding:14px 20px;font-size:15px;font-weight:700;color:#0f172a;border-bottom:1px solid #f1f5f9;">{{$submission->name}}</td>
              </tr>
              <tr>
                <td style="padding:14px 20px;font-size:12px;font-weight:700;color:#94a3b8;text-transform:uppercase;border-bottom:1px solid #f1f5f9;">WhatsApp</td>
                <td style="padding:14px 20px;border-bottom:1px solid #f1f5f9;">
                  <a href="https://wa.me/{{$waNumber}}" style="font-size:15px;font-weight:700;color:#16a34a;text-decoration:none;">{{$submission->phone}} &#128172;</a>
                </td>
              </tr>
              @if($submission->email)
              <tr>
                <td style="padding:14px 20px;font-size:12px;font-weight:700;color:#94a3b8;text-transform:uppercase;border-bottom:1px solid #f1f5f9;">Email</td>
                <td style="padding:14px 20px;border-bottom:1px solid #f1f5f9;">
                  <a href="mailto:{{$submission->email}}" style="font-size:14px;color:#3D9E7A;text-decoration:none;">{{$submission->email}}</a>
                </td>
              </tr>
              @endif
              @if($submission->city)
              <tr>
                <td style="padding:14px 20px;font-size:12px;font-weight:700;color:#94a3b8;text-transform:uppercase;border-bottom:1px solid #f1f5f9;">Kota</td>
                <td style="padding:14px 20px;font-size:14px;color:#0f172a;border-bottom:1px solid #f1f5f9;">{{$submission->city}}</td>
              </tr>
              @endif
              <tr>
                <td style="padding:14px 20px;font-size:12px;font-weight:700;color:#94a3b8;text-transform:uppercase;">IP Address</td>
                <td style="padding:14px 20px;font-size:12px;color:#94a3b8;font-family:'Courier New',Courier,monospace;">{{$submission->ip_address ?? '&#8212;'}}</td>
              </tr>
            </table>
          </td></tr>
        </table>

        @if($submission->message)
        {{-- Message Box --}}
        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-left:4px solid #F59E0B;background:#fffbeb;border-radius:0 10px 10px 0;margin-bottom:24px;">
          <tr><td style="padding:16px 20px;">
            <p style="margin:0 0 6px;font-size:11px;font-weight:700;color:#b45309;text-transform:uppercase;letter-spacing:1px;">Pesan dari Pendaftar</p>
            <p style="margin:0;font-size:14px;color:#431407;line-height:1.7;white-space:pre-line;">{{$submission->message}}</p>
          </td></tr>
        </table>
        @endif

        {{-- CTA Buttons --}}
        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:16px;">
          <tr>
            <td style="padding-right:8px;">
              <a href="{{$adminUrl}}" style="display:block;padding:13px 16px;background:linear-gradient(135deg,#3D9E7A,#2D7A5C);color:#fff;text-decoration:none;font-weight:700;border-radius:10px;font-size:14px;text-align:center;">&#128203; Buka Admin Panel</a>
            </td>
            <td style="padding-left:8px;">
              <a href="https://wa.me/{{$waNumber}}" style="display:block;padding:13px 16px;background:#25D366;color:#fff;text-decoration:none;font-weight:700;border-radius:10px;font-size:14px;text-align:center;">&#128172; Balas via WhatsApp</a>
            </td>
          </tr>
        </table>

        @if($submission->email)
        <p style="margin:0;font-size:12px;color:#94a3b8;text-align:center;">Atau klik <strong>Reply</strong> pada email ini untuk membalas langsung ke <strong>{{$submission->email}}</strong></p>
        @endif

      </td>
    </tr>

    {{-- FOOTER --}}
    <tr>
      <td style="background:#1e293b;border-radius:0 0 16px 16px;padding:20px 40px;text-align:center;">
        <p style="margin:0 0 4px;font-size:14px;font-weight:700;color:#ffffff;">{{$siteName}}</p>
        <p style="margin:0 0 4px;font-size:11px;color:#64748b;">Email notifikasi admin &#8212; bersifat rahasia</p>
        <p style="margin:0;font-size:11px;color:#475569;">Submission #{{$submission->id}} &nbsp;&#183;&nbsp; &copy; {{date('Y')}} {{$siteName}}</p>
      </td>
    </tr>

  </table>
</td></tr>
</table>
</body>
</html>
