<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class PasswordChangeController extends Controller
{
    public function show()
    {
        return view('auth.change-password');
    }

    /**
     * Update password
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.required' => 'Password saat ini harus diisi',
            'current_password.current_password' => 'Password saat ini tidak sesuai',
            'password.required' => 'Password baru harus diisi',
            'password.confirmed' => 'Konfirmasi password tidak sesuai',
            'password.min' => 'Password minimal 8 karakter',
        ]);

        // Check if new password is same as default
        if (Hash::check('password', Hash::make($validated['password'])) ||
            $validated['password'] === 'password') {
            return back()->with('error', 'Password baru tidak boleh sama dengan password default');
        }

        // Update password
        $user = auth()->user();
        $user->password = Hash::make($validated['password']);
        $user->must_change_password = false;
        $user->save();

        return redirect()->route('articles.index')
            ->with('success', 'Password berhasil diubah. Silakan gunakan password baru untuk login berikutnya.');
    }
}
