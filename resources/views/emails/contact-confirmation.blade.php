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
<title>Konfirmasi Pendaftaran</title>
</head>
<body style="margin:0;padding:0;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;background:#f0fdf4;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f0fdf4;padding:40px 20px;">
<tr><td align="center">
  <table width="100%" cellpadding="0" cellspacing="0" style="max-width:580px;">

    {{-- Logo/Header --}}
    <tr>
      <td align="center" style="padding-bottom:24px;">
        <a href="{{ $siteUrl }}" style="text-decoration:none;">
          <span style="font-size:24px;font-weight:900;color:#3D9E7A;letter-spacing:-0.5px;">{{ $siteName }}</span>
        </a>
      </td>
    </tr>

    {{-- Main Card --}}
    <tr>
      <td style="background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.06);">

        {{-- Green accent bar --}}
        <table width="100%" cellpadding="0" cellspacing="0">
          <tr>
            <td style="background:linear-gradient(90deg,#3D9E7A,#2D7A5C);height:5px;"></td>
          </tr>
        </table>

        {{-- Body --}}
        <table width="100%" cellpadding="0" cellspacing="0">
          <tr>
            <td style="padding:40px 36px 32px;">

              {{-- Icon --}}
              <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
                <tr>
                  <td align="center">
                    <div style="width:64px;height:64px;background:#dcfce7;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:28px;line-height:64px;text-align:center;">✓</div>
                  </td>
                </tr>
              </table>

              <h1 style="margin:0 0 8px;font-size:22px;font-weight:800;color:#0f172a;text-align:center;">Pendaftaran Diterima!</h1>
              <p style="margin:0 0 28px;font-size:15px;color:#64748b;text-align:center;line-height:1.6;">Halo <strong>{{ $submission->name }}</strong>, terima kasih telah mendaftar di <strong>{{ $siteName }}</strong>.</p>

              {{-- Divider --}}
              <hr style="border:none;border-top:1px solid #f1f5f9;margin:0 0 24px;">

              {{-- Detail box --}}
              <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc;border-radius:12px;margin-bottom:24px;">
                <tr><td style="padding:20px 24px;">
                  <p style="margin:0 0 14px;font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:1.5px;">Detail Pendaftaran</p>
                  <table width="100%" cellpadding="6" cellspacing="0">
                    <tr>
                      <td style="font-size:13px;color:#64748b;width:40%;">Nama</td>
                      <td style="font-size:14px;font-weight:600;color:#0f172a;">{{ $submission->name }}</td>
                    </tr>
                    <tr>
                      <td style="font-size:13px;color:#64748b;">WhatsApp</td>
                      <td style="font-size:14px;font-weight:600;color:#0f172a;">{{ $submission->phone }}</td>
                    </tr>
                    @if($submission->email)
                    <tr>
                      <td style="font-size:13px;color:#64748b;">Email</td>
                      <td style="font-size:14px;color:#0f172a;">{{ $submission->email }}</td>
                    </tr>
                    @endif
                    @if($submission->city)
                    <tr>
                      <td style="font-size:13px;color:#64748b;">Kota</td>
                      <td style="font-size:14px;color:#0f172a;">{{ $submission->city }}</td>
                    </tr>
                    @endif
                    <tr>
                      <td style="font-size:13px;color:#64748b;">Diterima</td>
                      <td style="font-size:13px;color:#94a3b8;">{{ $submission->created_at->translatedFormat('d F Y, H:i') }}</td>
                    </tr>
                  </table>
                </td></tr>
              </table>

              @if($submission->message)
              {{-- Message quote --}}
              <table width="100%" cellpadding="0" cellspacing="0" style="border-left:4px solid #3D9E7A;background:#f0fdf4;border-radius:0 8px 8px 0;margin-bottom:24px;">
                <tr><td style="padding:14px 18px;">
                  <p style="margin:0 0 6px;font-size:11px;font-weight:700;color:#2D7A5C;text-transform:uppercase;letter-spacing:1px;">Pesan Anda</p>
                  <p style="margin:0;font-size:14px;color:#1e3a2f;line-height:1.7;white-space:pre-line;">{{ $submission->message }}</p>
                </td></tr>
              </table>
              @endif

              <p style="margin:0 0 24px;font-size:14px;color:#475569;line-height:1.7;text-align:center;">
                Tim kami akan menghubungi Anda dalam <strong>1×24 jam</strong>.<br>
                Jika ada pertanyaan, balas email ini langsung.
              </p>

            </td>
          </tr>
        </table>

        {{-- Footer in card --}}
        <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc;border-top:1px solid #f1f5f9;">
          <tr>
            <td style="padding:20px 36px;">
              <p style="margin:0;font-size:13px;color:#64748b;line-height:1.8;">
                <strong style="color:#0f172a;">{{ $siteName }}</strong><br>
                @if($adminEmail)✉ <a href="mailto:{{ $adminEmail }}" style="color:#3D9E7A;text-decoration:none;">{{ $adminEmail }}</a><br>@endif
                @if($adminPhone)📞 {{ $adminPhone }}@endif
              </p>
            </td>
          </tr>
        </table>

      </td>
    </tr>

    {{-- Bottom note --}}
    <tr>
      <td align="center" style="padding-top:20px;">
        <p style="margin:0;font-size:11px;color:#94a3b8;">Email ini dikirim otomatis. Balas email ini untuk menghubungi tim kami.</p>
      </td>
    </tr>

  </table>
</td></tr>
</table>
</body>
</html>
