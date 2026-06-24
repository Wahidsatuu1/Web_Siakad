<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * AnnouncementController
 *
 * Controller ini menangani semua logika untuk manajemen pengumuman
 * oleh Admin, termasuk membuat, melihat, memperbarui, dan menghapus pengumuman.
 */
class AnnouncementController extends Controller
{

    public function index()
    {
        // Mengambil semua pengumuman, diurutkan dari yang terbaru,
        // dan memuat relasi 'user' untuk menampilkan nama pembuatnya.
        $announcements = Announcement::with('user')->latest()->paginate(10); // Paginate untuk data yang banyak

        // Mengirim data ke view 'admin.announcements.index'
        return view('admin.announcements.index', compact('announcements'));
    }

    public function store(Request $request)
    {
        // Validasi input dari form
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'target_role' => 'required|in:Semua,Admin,Dosen,Mahasiswa',
        ]);

        // Membuat pengumuman melalui relasi dengan user yang sedang login.
        // Ini secara otomatis akan mengisi kolom 'user_id'.
        Auth::user()->announcements()->create($validatedData);

        // Mengembalikan respons JSON untuk form AJAX
        return response()->json(['success' => 'Pengumuman berhasil diposting!']);
    }

    public function edit(Announcement $announcement)
    {
        // Mengirim data pengumuman yang spesifik ke view 'admin.announcements.edit'
        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        // Validasi input dari form edit
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'target_role' => 'required|in:Semua,Admin,Dosen,Mahasiswa',
        ]);

        // Update data pengumuman
        $announcement->update($validatedData);

        // Redirect kembali ke halaman daftar pengumuman dengan pesan sukses
        return redirect()->route('admin.announcements.index')->with('success', 'Pengumuman berhasil diperbarui.');
    }
    public function destroy(Announcement $announcement)
    {
        // Hapus data pengumuman
        $announcement->delete();

        // Mengembalikan respons JSON untuk AJAX request
        return response()->json(['success' => 'Pengumuman berhasil dihapus.']);
    }
}
