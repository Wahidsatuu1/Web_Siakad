<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PresensiMahasiswa;
use App\Models\Mahasiswa;
use App\Models\PengampuMataKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class KelolaPresensiController extends Controller
{
    public function index()
    {
        $presensiRecords = PresensiMahasiswa::with([
            'mahasiswa', 
            'pengampuMataKuliah.mataKuliah', 
            'pengampuMataKuliah.dosen'
        ])->orderBy('tanggal', 'desc')->orderBy('id', 'desc')->get();
        
        return view('admin.kelola_presensi.index', compact('presensiRecords'));
    }

    public function create()
    {
        $pengampuMataKuliahs = PengampuMataKuliah::with(['dosen', 'mataKuliah'])->get();
        $mahasiswas = Mahasiswa::orderBy('nama')->get();
        return view('admin.kelola_presensi.create', compact('pengampuMataKuliahs', 'mahasiswas'));
    }

    public function store(Request $request)
    {
        $this->validatePresensi($request);

        PresensiMahasiswa::create($request->all());

        return response()->json(['success' => 'Data presensi berhasil ditambahkan!']);
    }

    public function edit(PresensiMahasiswa $presensiMahasiswa)
    {
        $presensiMahasiswa->load(['mahasiswa', 'pengampuMataKuliah.mataKuliah', 'pengampuMataKuliah.dosen']);
        return view('admin.kelola_presensi.edit', compact('presensiMahasiswa'));
    }

    public function update(Request $request, PresensiMahasiswa $presensiMahasiswa)
    {
        $this->validatePresensi($request, $presensiMahasiswa->id);

        $presensiMahasiswa->update($request->only(['status_kehadiran', 'keterangan']));

        return response()->json(['success' => 'Status presensi berhasil diperbarui.']);
    }

    public function destroy(PresensiMahasiswa $presensiMahasiswa)
    {
        $presensiMahasiswa->delete();
        return response()->json(['success' => 'Data presensi berhasil dihapus.']);
    }

    // Helper function untuk validasi
    private function validatePresensi(Request $request, $ignoreId = null)
    {
        $request->validate([
            // PERUBAHAN: Validasi hanya untuk Hadir dan Tidak Hadir
            'status_kehadiran' => ['required', Rule::in(['Hadir', 'Tidak Hadir'])],
            'keterangan' => 'nullable|string|max:255',
            // Jika ini adalah form 'create', validasi field lainnya
            'mahasiswa_id' => ($ignoreId ? 'sometimes' : 'required') . '|exists:mahasiswas,id',
            'pengampu_mata_kuliah_id' => ($ignoreId ? 'sometimes' : 'required') . '|exists:pengampu_mata_kuliahs,id',
            'tanggal' => ($ignoreId ? 'sometimes' : 'required') . '|date',
        ]);
    }
}
