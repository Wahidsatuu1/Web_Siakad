<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AbsenDosen;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AbsenDosenController extends Controller
{
    /**
     * Menampilkan daftar absen dosen menggunakan DataTables.
     */
    public function index()
    {
        $absenDosens = AbsenDosen::with('dosen')->latest('tanggal')->get();
        return view('admin.absen_dosen.index', compact('absenDosens'));
    }

    /**
     * Menampilkan form untuk membuat data absen baru.
     */
    public function create()
    {
        $dosens = Dosen::orderBy('nama')->get();
        return view('admin.absen_dosen.create', compact('dosens'));
    }

    /**
     * Menyimpan data absen baru via AJAX.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'dosen_id' => 'required|exists:dosens,id',
            'tanggal' => 'required|date',
            'waktu_masuk' => 'nullable|date_format:H:i',
            'waktu_keluar' => 'nullable|date_format:H:i|after_or_equal:waktu_masuk',
            // PERUBAHAN: Validasi hanya untuk Hadir dan Tidak Hadir
            'status' => ['required', Rule::in(['Hadir', 'Tidak Hadir'])],
            'keterangan' => 'nullable|string|max:255',
        ]);

        AbsenDosen::create($validated);

        return response()->json(['success' => 'Absen dosen berhasil ditambahkan.']);
    }

    /**
     * Menampilkan form untuk mengedit data absen.
     */
    public function edit(AbsenDosen $absenDosen)
    {
        $dosens = Dosen::orderBy('nama')->get();
        return view('admin.absen_dosen.edit', compact('absenDosen', 'dosens'));
    }

    /**
     * Memperbarui data absen via AJAX.
     */
    public function update(Request $request, AbsenDosen $absenDosen)
    {
        $validated = $request->validate([
            'dosen_id' => 'required|exists:dosens,id',
            'tanggal' => 'required|date',
            'waktu_masuk' => 'nullable|date_format:H:i',
            'waktu_keluar' => 'nullable|date_format:H:i|after_or_equal:waktu_masuk',
            // PERUBAHAN: Validasi hanya untuk Hadir dan Tidak Hadir
            'status' => ['required', Rule::in(['Hadir', 'Tidak Hadir'])],
            'keterangan' => 'nullable|string|max:255',
        ]);

        $absenDosen->update($validated);

        return response()->json(['success' => 'Absen dosen berhasil diperbarui.']);
    }

    /**
     * Menghapus data absen via AJAX.
     */
    public function destroy(AbsenDosen $absenDosen)
    {
        $dosenName = $absenDosen->dosen->nama;
        $absenDosen->delete();

        return response()->json(['success' => "Data absen untuk $dosenName berhasil dihapus."]);
    }
}
