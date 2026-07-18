<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfilController extends Controller
{
    /**
     * Tampilkan form edit profil milik sendiri.
     */
    public function edit()
    {
        $user = Auth::user();

        return view('profil.edit', compact('user'));
    }

    /**
     * Simpan perubahan nama, email, dan (opsional) password.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'password_lama' => ['required_with:password', 'nullable'],
        ]);

        // Jika ingin ganti password, wajib verifikasi password lama dulu.
        if (!empty($validated['password'])) {
            if (!Hash::check($validated['password_lama'], $user->password)) {
                return back()
                    ->withErrors(['password_lama' => 'Password lama tidak sesuai.'])
                    ->onlyInput('nama', 'email');
            }

            if (Hash::check($validated['password'], $user->password)) {
                return back()
                    ->withErrors(['password' => 'Password baru tidak boleh sama dengan password lama.'])
                    ->onlyInput('nama', 'email');
            }

            $user->password = Hash::make($validated['password']);
        }

        $user->nama = $validated['nama'];
        $user->email = $validated['email'];
        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
