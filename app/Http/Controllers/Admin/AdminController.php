<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function showChangePasswordForm()
    {
        return view('admin.settings.change_password'); 
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            // validasi untuk 'current_password'
            // 'current_password' => ['required', 'string', function ($attribute, $value, $fail) {
            //     if (!Hash::check($value, Auth::user()->password)) {
            //         $fail('Password saat ini salah.');
            //     }
            // }],
            'new_password' => 'required|string|min:8|confirmed',
        ], [
            //  pesan validasi untuk 'current_password.required'
            // 'current_password.required' => 'Password saat ini wajib diisi.',
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.min' => 'Password baru minimal 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->back()->with('success', 'Password berhasil diubah!');
    }
}