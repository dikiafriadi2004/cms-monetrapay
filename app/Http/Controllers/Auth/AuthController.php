<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Sentry\Breadcrumb;

class AuthController extends Controller
{
    /**
     * Maximum failed login attempts before lockout.
     */
    protected const MAX_ATTEMPTS = 5;

    /**
     * Lockout duration in seconds (15 minutes).
     */
    protected const DECAY_SECONDS = 900;

    /**
     * Show the login form.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.login');
    }

    /**
     * Handle login request with rate limiting and audit logging.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $key = $this->throttleKey($request);

        // Block if too many failed attempts
        if (RateLimiter::tooManyAttempts($key, self::MAX_ATTEMPTS)) {
            $seconds = RateLimiter::availableIn($key);
            $minutes = ceil($seconds / 60);

            AuditLog::record('login.locked', [
                'email' => $credentials['email'],
                'ip' => $request->ip(),
                'lockout_seconds' => $seconds,
            ]);

            throw ValidationException::withMessages([
                'email' => "Terlalu banyak percobaan login gagal. Coba lagi dalam {$minutes} menit.",
            ]);
        }

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            RateLimiter::clear($key);
            $request->session()->regenerate();

            $user = Auth::user();

            // If 2FA is enabled, log out immediately and require OTP challenge
            if ($user->hasTwoFactorEnabled()) {
                Auth::logout();
                $request->session()->put('2fa.user_id', $user->id);
                $request->session()->put('2fa.remember', $remember);

                AuditLog::record('login.success_2fa_pending', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'ip' => $request->ip(),
                ]);

                return redirect()->route('two-factor.challenge');
            }

            AuditLog::record('login.success', [
                'user_id' => Auth::id(),
                'email' => Auth::user()->email,
                'ip' => $request->ip(),
            ]);

            $this->sentryBreadcrumb('login.success', [
                'user_id' => Auth::id(),
            ]);

            return redirect()->intended(route('admin.dashboard'));
        }

        // Failed login
        RateLimiter::hit($key, self::DECAY_SECONDS);
        $remaining = self::MAX_ATTEMPTS - RateLimiter::attempts($key);

        AuditLog::record('login.failed', [
            'email' => $credentials['email'],
            'ip' => $request->ip(),
            'remaining_attempts' => max(0, $remaining),
        ]);

        $this->sentryBreadcrumb('login.failed', [
            'remaining' => max(0, $remaining),
        ]);

        $message = 'Email atau password yang Anda masukkan salah.';
        if ($remaining > 0 && $remaining <= 2) {
            $message .= " Sisa {$remaining} percobaan sebelum akun dikunci.";
        }

        return back()->withErrors(['email' => $message])->onlyInput('email');
    }

    /**
     * Handle logout request.
     */
    public function logout(Request $request)
    {
        if ($user = Auth::user()) {
            AuditLog::record('logout', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $request->ip(),
            ]);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Build a unique rate-limit key per email + IP combo.
     * Email is lowercased to prevent case-based bypass.
     */
    protected function throttleKey(Request $request): string
    {
        return Str::lower((string) $request->input('email')).'|'.$request->ip();
    }

    /**
     * Add a Sentry breadcrumb (silent if Sentry isn't configured).
     */
    protected function sentryBreadcrumb(string $message, array $data = []): void
    {
        if (! config('sentry.dsn')) {
            return;
        }

        try {
            \Sentry\addBreadcrumb(new Breadcrumb(
                level: Breadcrumb::LEVEL_INFO,
                type: Breadcrumb::TYPE_DEFAULT,
                category: 'auth',
                message: $message,
                metadata: $data,
            ));
        } catch (\Throwable $e) {
            // silent
        }
    }
}
