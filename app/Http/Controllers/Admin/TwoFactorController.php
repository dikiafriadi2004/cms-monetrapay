<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorController extends Controller
{
    protected Google2FA $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA;
    }

    /**
     * Show 2FA setup page with QR code.
     */
    public function setup()
    {
        $user = auth()->user();

        // Generate a new secret if user doesn't have one yet
        if (empty($user->two_factor_secret)) {
            $secret = $this->google2fa->generateSecretKey();
            $user->update(['two_factor_secret' => $secret]);
        }

        $qrCodeUrl = $this->google2fa->getQRCodeUrl(
            config('app.name', 'MonetraPay'),
            $user->email,
            $user->two_factor_secret,
        );

        return view('admin.two-factor.setup', [
            'user' => $user,
            'qrCodeUrl' => $qrCodeUrl,
            'secret' => $user->two_factor_secret,
        ]);
    }

    /**
     * Enable 2FA after confirming the first TOTP code.
     */
    public function enable(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'digits:6'],
        ], [
            'code.required' => 'Kode OTP wajib diisi.',
            'code.digits' => 'Kode OTP harus 6 digit angka.',
        ]);

        $user = auth()->user();

        $valid = $this->google2fa->verifyKey(
            $user->two_factor_secret,
            $validated['code'],
        );

        if (! $valid) {
            return back()->withErrors(['code' => 'Kode OTP tidak valid. Pastikan waktu perangkat Anda sinkron.']);
        }

        // Generate 8 one-time recovery codes
        $recoveryCodes = collect(range(1, 8))
            ->map(fn () => Str::upper(Str::random(4)).'-'.Str::upper(Str::random(4)))
            ->all();

        $user->update([
            'two_factor_enabled' => true,
            'two_factor_recovery_codes' => json_encode($recoveryCodes),
        ]);

        AuditLog::record('two_factor.enabled');

        return view('admin.two-factor.recovery-codes', [
            'recoveryCodes' => $recoveryCodes,
        ]);
    }

    /**
     * Disable 2FA after verifying password.
     */
    public function disable(Request $request)
    {
        $validated = $request->validate([
            'password' => ['required', 'current_password'],
        ], [
            'password.current_password' => 'Password tidak cocok.',
        ]);

        $user = auth()->user();
        $user->update([
            'two_factor_enabled' => false,
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
        ]);

        AuditLog::record('two_factor.disabled');

        return redirect()->route('admin.profile.edit')
            ->with('success', '2FA berhasil dinonaktifkan.');
    }

    /**
     * Show the OTP challenge page (after password correct, before dashboard).
     */
    public function challenge()
    {
        if (! session()->has('2fa.user_id')) {
            return redirect()->route('login');
        }

        return view('admin.two-factor.challenge');
    }

    /**
     * Verify the OTP code during login challenge.
     */
    public function verify(Request $request)
    {
        $userId = session('2fa.user_id');
        if (! $userId) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'code' => ['required', 'string', 'max:20'],
        ]);

        $user = User::find($userId);
        if (! $user) {
            return redirect()->route('login');
        }

        $code = preg_replace('/\s+/', '', $validated['code']); // strip spaces (recovery codes have dash)

        // Check TOTP code
        $validOtp = $this->google2fa->verifyKey($user->two_factor_secret, $code);

        // Check recovery code
        $validRecovery = false;
        if (! $validOtp) {
            $recoveryCodes = $user->getRecoveryCodes();
            $codeUpper = strtoupper($code);
            if (in_array($codeUpper, $recoveryCodes, true)) {
                $validRecovery = true;
                // Consume recovery code — remove it from the list
                $remaining = array_filter($recoveryCodes, fn ($c) => $c !== $codeUpper);
                $user->update(['two_factor_recovery_codes' => json_encode(array_values($remaining))]);
                AuditLog::record('two_factor.recovery_code_used', ['remaining' => count($remaining)]);
            }
        }

        if (! $validOtp && ! $validRecovery) {
            return back()->withErrors(['code' => 'Kode tidak valid. Cek aplikasi authenticator Anda.']);
        }

        // Auth passed — actually log in the user
        auth()->loginUsingId($userId);
        session()->forget('2fa.user_id');
        $request->session()->regenerate();

        AuditLog::record('two_factor.verified', ['method' => $validRecovery ? 'recovery' : 'totp']);

        return redirect()->intended(route('admin.dashboard'));
    }
}
