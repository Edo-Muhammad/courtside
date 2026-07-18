<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Password as PasswordRule;

class PasswordResetController extends Controller
{
    /**
     * Tampilkan form untuk memasukkan email dan meminta link reset password.
     */
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Kirim link reset password ke email (jika akun terdaftar).
     * Selama MAIL_MAILER=log di .env, link akan tercatat di storage/logs/laravel.log
     * alih-alih benar-benar terkirim, karena project ini belum terhubung ke SMTP asli.
     */
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', 'Link reset password telah dikirim. Karena aplikasi ini belum terhubung ke email asli, silakan cek file storage/logs/laravel.log untuk mengambil link tersebut.')
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Tampilkan form set password baru (diakses lewat link reset).
     */
    public function showResetForm(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * Proses simpan password baru berdasarkan token yang valid.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', PasswordRule::min(8)],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', 'Password berhasil direset. Silakan login dengan password baru kamu.')
            : back()->withErrors(['email' => __($status)]);
    }
}
