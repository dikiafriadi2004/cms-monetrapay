@php
    $siteName = \App\Models\Setting::get('site_name', 'general') ?? config('app.name');
    $adminEmail = \App\Models\Setting::get('contact_email', 'general');
    $adminPhone = \App\Models\Setting::get('contact_phone', 'general');
    $siteUrl = config('app.url', 'https://monetrapay.com');
    $logoPath = \App\Models\Setting::get('logo', 'general');
    $logoUrl = $logoPath ? url(\Illuminate\Support\Facades\Storage::url($logoPath)) : null;
    $subject = $submission->subject ?? null;
    $isRegistration = $subject === 'Daftar Agen/Reseller' || empty($subject);
    $emailTitle = match($subject) {
        'Daftar Agen/Reseller' => 'Pendaftaran Agen Diterima!',
        'Bantuan transaksi' => 'Laporan Anda Diterima',
        'Kendala teknis' => 'Laporan Kendala Diterima',
        'Kerja sama' => 'Proposal Kerja Sama Diterima',
        default => 'Pesan Anda Telah Diterima',
    };
    $emailGreeting = match($subject) {
        'Daftar Agen/Reseller' => 'mendaftar sebagai agen di',
        'Bantuan transaksi', 'Kendala teknis' => 'menghubungi tim support',
        default => 'menghubungi',
    };
    $cardTitle = $isRegistration ? 'Detail Pendaftaran Anda' : 'Detail Pesan Anda';
    $nextStepsTitle = $isRegistration ? 'Langkah Selanjutnya' : 'Yang Akan Kami Lakukan';
    $steps = $isRegistration ? [
        ['title' => 'Tim kami akan meninjau data Anda', 'desc' => 'Estimasi waktu: 1&#8211;2 jam kerja'],
        ['title' => 'Kami akan menghubungi Anda via WhatsApp', 'desc' => 'Nomor: ' . e($submission->phone)],
        ['title' => 'Selamat bergabung sebagai Agen!', 'desc' => 'Akses panel agen dan mulai berjualan'],
    ] : [
        ['title' => 'Tim kami akan meninjau pesan Anda', 'desc' => 'Kami segera memproses permintaan Anda'],
        ['title' => 'Kami akan menghubungi Anda dalam 1&#215;24 jam', 'desc' => 'Nomor: ' . e($submission->phone)],
        ['title' => 'Masalah Anda akan segera diselesaikan', 'desc' => 'Tim kami berpengalaman menangani berbagai kendala'],
    ];
    $headerSubtitle = $isRegistration ? 'Konfirmasi Pendaftaran' : 'Konfirmasi Pesan';
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{$headerSubtitle}} - {{$siteName}}</title>
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
        <p style="margin:0;font-size:11px;color:#a7f3d0;font-weight:600;letter-spacing:2.5px;text-transform:uppercase;">{{$headerSubtitle}}</p>
      </td>
    </tr>

    {{-- BODY --}}
    <tr>
      <td style="background:#ffffff;padding:40px 40px 32px;">

        {{-- Icon --}}
        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:24px;">
          <tr><td align="center">
            <div style="width:72px;height:72px;background:#dcfce7;border-radius:50%;display:inline-block;line-height:72px;text-align:center;font-size:32px;">&#9989;</div>
          </td></tr>
        </table>

        <h1 style="margin:0 0 8px;font-size:24px;font-weight:900;color:#0f172a;text-align:center;">{{$emailTitle}}</h1>
        <p style="margin:0 0 28px;font-size:15px;color:#475569;text-align:center;line-height:1.7;">Halo <strong>{{$submission->name}}</strong>, terima kasih telah {{$emailGreeting}} <strong>{{$siteName}}</strong>.</p>

        {{-- Summary Card --}}
        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f8fafc;border-radius:12px;border:1px solid #e2e8f0;margin-bottom:28px;">
          <tr><td style="padding:20px 24px;">
            <p style="margin:0 0 14px;font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:1.5px;">{{$cardTitle}}</p>
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td style="padding:8px 0;font-size:13px;color:#64748b;width:38%;border-bottom:1px solid #f1f5f9;">Nama Lengkap</td>
                <td style="padding:8px 0;font-size:14px;font-weight:700;color:#0f172a;border-bottom:1px solid #f1f5f9;">{{$submission->name}}</td>
              </tr>
              <tr>
                <td style="padding:8px 0;font-size:13px;color:#64748b;border-bottom:1px solid #f1f5f9;">WhatsApp</td>
                <td style="padding:8px 0;font-size:14px;font-weight:700;color:#0f172a;border-bottom:1px solid #f1f5f9;">{{$submission->phone}}</td>
              </tr>
              @if($submission->email)
              <tr>
                <td style="padding:8px 0;font-size:13px;color:#64748b;border-bottom:1px solid #f1f5f9;">Email</td>
                <td style="padding:8px 0;font-size:14px;color:#0f172a;border-bottom:1px solid #f1f5f9;">{{$submission->email}}</td>
              </tr>
              @endif
              @if($submission->subject)
              <tr>
                <td style="padding:8px 0;font-size:13px;color:#64748b;border-bottom:1px solid #f1f5f9;">Topik</td>
                <td style="padding:8px 0;font-size:14px;color:#0f172a;border-bottom:1px solid #f1f5f9;">{{$submission->subject}}</td>
              </tr>
              @elseif($submission->city)
              <tr>
                <td style="padding:8px 0;font-size:13px;color:#64748b;border-bottom:1px solid #f1f5f9;">Kota</td>
                <td style="padding:8px 0;font-size:14px;color:#0f172a;border-bottom:1px solid #f1f5f9;">{{$submission->city}}</td>
              </tr>
              @endif
              <tr>
                <td style="padding:8px 0;font-size:13px;color:#64748b;">Waktu Daftar</td>
                <td style="padding:8px 0;font-size:13px;color:#94a3b8;">{{$submission->created_at->translatedFormat('d F Y, H:i')}} WIB</td>
              </tr>
            </table>
          </td></tr>
        </table>

        {{-- Next Steps --}}
        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:28px;">
          <tr><td>
            <p style="margin:0 0 16px;font-size:13px;font-weight:700;color:#0f172a;text-transform:uppercase;letter-spacing:1px;">&#128338; {{$nextStepsTitle}}</p>
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
              @foreach($steps as $i => $step)
              <tr>
                <td style="padding:10px 0;vertical-align:top;width:40px;">
                  <div style="width:28px;height:28px;background:{{$i === 2 && $isRegistration ? 'linear-gradient(135deg,#F59E0B,#D97706)' : 'linear-gradient(135deg,#3D9E7A,#2D7A5C)'}};border-radius:50%;display:inline-block;line-height:28px;text-align:center;font-size:12px;font-weight:900;color:#fff;">{{$i + 1}}</div>
                </td>
                <td style="padding:10px 0 10px 8px;">
                  <p style="margin:0 0 2px;font-size:14px;font-weight:700;color:#0f172a;">{!! $step['title'] !!}</p>
                  <p style="margin:0;font-size:13px;color:#64748b;">{!! $step['desc'] !!}</p>
                </td>
              </tr>
              @endforeach
            </table>
          </td></tr>
        </table>

        @if($submission->message)
        {{-- Message Quote --}}
        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-left:4px solid #3D9E7A;background:#f0fdf4;border-radius:0 10px 10px 0;margin-bottom:28px;">
          <tr><td style="padding:16px 20px;">
            <p style="margin:0 0 6px;font-size:11px;font-weight:700;color:#2D7A5C;text-transform:uppercase;letter-spacing:1px;">Pesan Anda</p>
            <p style="margin:0;font-size:14px;color:#1e3a2f;line-height:1.7;white-space:pre-line;">{{$submission->message}}</p>
          </td></tr>
        </table>
        @endif

        {{-- CTA --}}
        <table width="100%" cellpadding="0" cellspacing="0" border="0">
          <tr><td align="center">
            <a href="{{$siteUrl}}" style="display:inline-block;padding:14px 36px;background:linear-gradient(135deg,#3D9E7A,#2D7A5C);color:#fff;text-decoration:none;font-weight:700;border-radius:10px;font-size:15px;box-shadow:0 4px 15px rgba(61,158,122,0.4);letter-spacing:0.3px;">Kunjungi Website Kami</a>
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
        <p style="margin:0;font-size:11px;color:#475569;">&copy; {{date('Y')}} {{$siteName}}. All rights reserved.</p>
      </td>
    </tr>

  </table>
</td></tr>
</table>
</body>
</html>
