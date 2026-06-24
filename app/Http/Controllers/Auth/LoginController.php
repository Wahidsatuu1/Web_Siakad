<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Tampilkan form login.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle permintaan login.
     */
    public function login(Request $request)
    {
        $input = $request->validate([
            'identifier' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $identifier = $input['identifier'];
        $password = $input['password'];
        $user = null;
        $attemptSuccess = false;

        // Coba otentikasi sebagai dosen/admin (menggunakan email)
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            if (Auth::attempt(['email' => $identifier, 'password' => $password])) {
                $user = Auth::user();
                if ($user->role !== 'mahasiswa') {
                    $attemptSuccess = true;
                } else {
                    Auth::logout();
                }
            }
        }
        
        // Jika belum berhasil, coba otentikasi sebagai mahasiswa (menggunakan NIM)
        if (!$attemptSuccess) {
            $mahasiswa = Mahasiswa::where('nim', $identifier)->first();
            if ($mahasiswa && $mahasiswa->user) {
                if (Auth::attempt(['email' => $mahasiswa->user->email, 'password' => $password, 'role' => 'mahasiswa'])) {
                    $user = Auth::user();
                    $attemptSuccess = true;
                }
            }
        }

        // Jika otentikasi berhasil
        if ($attemptSuccess && $user) {
            $request->session()->regenerate();
            
            $redirectUrl = $this->getRedirectPath($user->role);
            
            // PERUBAHAN: Kembalikan respon JSON jika ini adalah request AJAX
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Login berhasil! Mengarahkan ke dashboard...',
                    'redirect_url' => $redirectUrl
                ]);
            }

            return redirect()->intended($redirectUrl);
        }

        // Jika otentikasi gagal
        $errorMessage = 'Identifikasi (Email/NIM) atau password salah.';
        
        // PERUBAHAN: Kembalikan respon JSON jika ini adalah request AJAX
        if ($request->ajax()) {
            return response()->json(['message' => $errorMessage], 422); // 422 Unprocessable Entity
        }

        return back()->withErrors(['identifier' => $errorMessage])->onlyInput('identifier');
    }
    
    /**
     * Helper untuk mendapatkan path redirect berdasarkan role.
     */
    protected function getRedirectPath(string $role): string
    {
        switch ($role) {
            case 'admin':
                return '/admin/dashboard';
            case 'dosen':
                return '/dosen/dashboard';
            case 'mahasiswa':
                return '/mahasiswa/dashboard';
            default:
                return '/home';
        }
    }

    /**
     * Handle logout user.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
