<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class DosenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengambil semua data untuk dikelola oleh DataTables di sisi klien
        $dosens = Dosen::with('user')->orderBy('nama')->get();
        return view('admin.dosen.index', compact('dosens'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.dosen.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email|unique:dosens,email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'nidn' => 'nullable|string|max:20|unique:dosens,nidn',
            'prodi' => 'nullable|string|max:255',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // Buat akun user terlebih dahulu
                $user = User::create([
                    'name' => $request->nama,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role' => 'dosen',
                ]);

                // Buat profil dosen yang terhubung dengan user
                $user->dosen()->create([
                    'nama' => $request->nama,
                    'email' => $request->email,
                    'nidn' => $request->nidn,
                    'prodi' => $request->prodi,
                ]);
            });
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menambahkan dosen: ' . $e->getMessage()], 500);
        }

        return response()->json(['success' => 'Akun dosen dan profil berhasil ditambahkan!']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dosen $dosen)
    {
        $dosen->load('user');
        return view('admin.dosen.edit', compact('dosen'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dosen $dosen)
    {
        $user = $dosen->user;
        
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id . '|unique:dosens,email,' . $dosen->id,
            'nidn' => 'nullable|string|max:20|unique:dosens,nidn,' . $dosen->id,
            'prodi' => 'nullable|string|max:255',
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        try {
            DB::transaction(function () use ($request, $dosen, $user) {
                $dosen->update($request->only(['nama', 'email', 'nidn', 'prodi']));
                
                $userData = ['name' => $request->nama, 'email' => $request->email];
                if ($request->filled('password')) {
                    $userData['password'] = Hash::make($request->password);
                }
                $user->update($userData);
            });
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal memperbarui data dosen: ' . $e->getMessage()], 500);
        }
        
        return response()->json(['success' => 'Data dosen berhasil diperbarui!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dosen $dosen)
    {
        // Pengecekan relasi, contoh: jika dosen masih punya jadwal mengajar
        if ($dosen->pengampuMataKuliah()->exists()) {
            return response()->json(['error' => 'Dosen tidak dapat dihapus karena masih memiliki jadwal mengajar.'], 422);
        }

        try {
            DB::transaction(function () use ($dosen) {
                // Hapus user terkait, yang akan cascade menghapus profil dosen
                $dosen->user()->delete();
            });
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menghapus data: ' . $e->getMessage()], 500);
        }

        return response()->json(['success' => 'Data dosen berhasil dihapus!']);
    }
}
