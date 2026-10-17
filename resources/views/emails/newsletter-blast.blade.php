@php
    $siteUrl = config('app.url', 'https://monetrapay.com');
    $unsubToken = $subscriber->confirmation_token ?? '';
    $unsubUrl = $unsubToken ? url(route('newsletter.unsubscribe', $unsubToken, false)) : '#';
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $campaign->subject }}</title>
</head>
<body style="margin:0;padding:0;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;background:#f0fdf4;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f0fdf4;padding:40px 20px;">
<tr><td align="center">
  <table width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;">

    {{-- Header --}}
    <tr>
      <td align="center" style="padding-bottom:24px;">
        <a href="{{ $siteUrl }}" style="text-decoration:none;">
          <span style="font-size:24px;font-weight:900;color:#3D9E7A;">{{ $siteName }}</span>
        </a>
      </td>
    </tr>

    {{-- Card --}}
    <tr>
      <td style="background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.06);">
        <table width="100%" cellpadding="0" cellspacing="0">
          <tr><td style="background:linear-gradient(90deg,#3D9E7A,#2D7A5C);height:5px;"></td></tr>
        </table>

        <table width="100%" cellpadding="0" cellspacing="0">
          <tr><td style="padding:36px;">
            {{-- Subject --}}
            <h1 style="margin:0 0 24px;font-size:22px;font-weight:800;color:#0f172a;line-height:1.3;">{{ $campaign->subject }}</h1>
            <hr style="border:none;border-top:1px solid #f1f5f9;margin:0 0 24px;">

            {{-- Content --}}
            <div style="font-size:15px;color:#334155;line-height:1.8;">
              {!! $campaign->content !!}
            </div>

          </td></tr>
        </table>

        {{-- Footer --}}
        <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc;border-top:1px solid #f1f5f9;">
          <tr><td style="padding:20px 36px;text-align:center;">
            <p style="margin:0 0 10px;font-size:13px;color:#64748b;">
              Anda menerima email ini karena berlangganan newsletter <strong>{{ $siteName }}</strong>.
            </p>
            @if($unsubToken)
            <a href="{{ $unsubUrl }}" style="font-size:12px;color:#94a3b8;">Berhenti langganan</a>
            @endif
          </td></tr>
        </table>
      </td>
    </tr>

  </table>
</td></tr>
</table>
</body>
</html>
