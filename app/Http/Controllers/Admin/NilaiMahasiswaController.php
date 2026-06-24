<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NilaiMahasiswa;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Dosen;
use App\Models\PengampuMataKuliah;
use App\Models\PresensiMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class NilaiMahasiswaController extends Controller
{
    public function index()
    {
        // Mengambil semua data untuk dikelola oleh DataTables di sisi klien
        $nilaiMahasiswas = NilaiMahasiswa::with(['mahasiswa', 'mataKuliah', 'dosen'])->get();
        return view('admin.nilai_mahasiswa.index', compact('nilaiMahasiswas'));
    }

    public function create()
    {
        $mahasiswas = Mahasiswa::orderBy('nama')->get();
        $mataKuliahs = MataKuliah::orderBy('nama_mk')->get();
        $dosens = Dosen::orderBy('nama')->get();
        $kelasOptions = ['06TPLP001', '06TPLP002', '06TPLP003', '06TPLP004', '06TPLP005', '06TPLP006', '06TPLP007', '06TPLP008', '06TPLP009', '06TPLP0010'];
        return view('admin.nilai_mahasiswa.create', compact('mahasiswas', 'mataKuliahs', 'dosens', 'kelasOptions'));
    }

    public function store(Request $request)
    {
        $this->validateNilai($request);

        DB::transaction(function () use ($request) {
            $nilaiData = $this->calculateNilai($request);
            NilaiMahasiswa::create($nilaiData);
        });

        return response()->json(['success' => 'Nilai mahasiswa berhasil ditambahkan!']);
    }

    public function edit(NilaiMahasiswa $nilaiMahasiswa)
    {
        $mahasiswas = Mahasiswa::orderBy('nama')->get();
        $mataKuliahs = MataKuliah::orderBy('nama_mk')->get();
        $dosens = Dosen::orderBy('nama')->get();
        $kelasOptions = ['06TPLP001', '06TPLP002', '06TPLP003', '06TPLP004', '06TPLP005', '06TPLP006', '06TPLP007', '06TPLP008', '06TPLP009', '06TPLP0010'];
        return view('admin.nilai_mahasiswa.edit', compact('nilaiMahasiswa', 'mahasiswas', 'mataKuliahs', 'dosens', 'kelasOptions'));
    }

    public function update(Request $request, NilaiMahasiswa $nilaiMahasiswa)
    {
        $this->validateNilai($request, $nilaiMahasiswa->id);

        DB::transaction(function () use ($request, $nilaiMahasiswa) {
            $nilaiData = $this->calculateNilai($request);
            $nilaiMahasiswa->update($nilaiData);
        });

        return response()->json(['success' => 'Nilai mahasiswa berhasil diperbarui!']);
    }

    public function destroy(NilaiMahasiswa $nilaiMahasiswa)
    {
        $nilaiMahasiswa->delete();
        return response()->json(['success' => 'Data nilai berhasil dihapus.']);
    }
    
    // AJAX Helpers
    public function getMahasiswaKelas(Mahasiswa $mahasiswa)
    {
        return response()->json(['kelas' => $mahasiswa->kelas]);
    }

    public function getDosenPengampu(Request $request)
    {
        $pengampu = PengampuMataKuliah::where('mata_kuliah_id', $request->mata_kuliah_id)
                                      ->where('kelas', $request->kelas)
                                      ->with('dosen')->first();
        if ($pengampu && $pengampu->dosen) {
            return response()->json([
                'dosen_id' => $pengampu->dosen_id,
                'dosen_nama' => $pengampu->dosen->nama
            ]);
        }
        return response()->json(['dosen_id' => null]);
    }

    // Private helper methods
    private function validateNilai(Request $request, $ignoreId = null)
    {
        $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswas,id',
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'dosen_id' => 'nullable|exists:dosens,id',
            'kelas' => ['required','string','max:10', Rule::unique('nilai_mahasiswas')->where(function ($query) use ($request) {
                return $query->where('mahasiswa_id', $request->mahasiswa_id)->where('mata_kuliah_id', $request->mata_kuliah_id);
            })->ignore($ignoreId)],
            'nilai_tugas' => 'nullable|numeric|min:0|max:100',
            'nilai_uts' => 'nullable|numeric|min:0|max:100',
            'nilai_uas' => 'nullable|numeric|min:0|max:100',
        ], ['kelas.unique' => 'Nilai untuk mahasiswa ini pada mata kuliah tersebut sudah ada.']);
    }

    private function calculateNilai(Request $request): array
    {
        $nilai_tugas = $request->nilai_tugas ?? 0;
        $nilai_uts = $request->nilai_uts ?? 0;
        $nilai_uas = $request->nilai_uas ?? 0;
        
        $pengampuMataKuliah = PengampuMataKuliah::where('mata_kuliah_id', $request->mata_kuliah_id)->where('kelas', $request->kelas)->first();
        $jumlahKehadiran = 0;
        if ($pengampuMataKuliah) {
            $jumlahKehadiran = PresensiMahasiswa::where('mahasiswa_id', $request->mahasiswa_id)
                ->where('pengampu_mata_kuliah_id', $pengampuMataKuliah->id)
                ->where('status_kehadiran', 'Hadir')->count();
        }

        $nilai_angka = ($nilai_tugas * 0.3) + ($nilai_uts * 0.3) + ($nilai_uas * 0.4);

        if ($nilai_angka >= 80) $nilai_huruf = 'A';
        elseif ($nilai_angka >= 70) $nilai_huruf = 'B';
        elseif ($nilai_angka >= 60) $nilai_huruf = 'C';
        elseif ($nilai_angka >= 50) $nilai_huruf = 'D';
        else $nilai_huruf = 'E';

        return array_merge($request->all(), [
            'kehadiran' => $jumlahKehadiran,
            'nilai_angka' => round($nilai_angka, 2),
            'nilai_huruf' => $nilai_huruf,
        ]);
    }
}
