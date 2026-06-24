<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengampuMataKuliah;
use App\Models\JadwalKuliah;
use App\Models\MataKuliah;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class PengampuMataKuliahController extends Controller
{
    public function index()
    {
        $sortedPengampu = PengampuMataKuliah::with(['mataKuliah', 'dosen'])
            ->orderBy(DB::raw("CASE hari WHEN 'Senin' THEN 1 WHEN 'Selasa' THEN 2 WHEN 'Rabu' THEN 3 WHEN 'Kamis' THEN 4 WHEN 'Jumat' THEN 5 WHEN 'Sabtu' THEN 6 ELSE 7 END"))
            ->orderBy('jam_mulai', 'asc')
            ->get();
            
        return view('admin.pengampu_mata_kuliah.index', ['pengampuMataKuliahs' => $sortedPengampu]);
    }

    public function create()
    {
        $mataKuliahs = MataKuliah::orderBy('nama_mk')->get();
        $dosens = Dosen::orderBy('nama')->get();
        $haris = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $kelasOptions = ['06TPLP001', '06TPLP002', '06TPLP003', '06TPLP004', '06TPLP005', '06TPLP005', '06TPLP006', '06TPLP007', '06TPLP008', '06TPLP009', '06TPLP0010'];
        return view('admin.pengampu_mata_kuliah.create', compact('mataKuliahs', 'dosens', 'haris', 'kelasOptions'));
    }

    public function store(Request $request)
    {
        $this->validateJadwal($request);

        DB::transaction(function () use ($request) {
            PengampuMataKuliah::create($request->all());
            JadwalKuliah::create($request->all());
        });

        return response()->json(['success' => 'Jadwal Mengajar berhasil ditambahkan!']);
    }

    public function edit(PengampuMataKuliah $pengampuMataKuliah)
    {
        $mataKuliahs = MataKuliah::orderBy('nama_mk')->get();
        $dosens = Dosen::orderBy('nama')->get();
        $haris = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $kelasOptions = ['06TPLP001', '06TPLP002', '06TPLP003', '06TPLP004', '06TPLP005', '06TPLP005', '06TPLP006', '06TPLP007', '06TPLP008', '06TPLP009', '06TPLP0010'];
        return view('admin.pengampu_mata_kuliah.edit', compact('pengampuMataKuliah', 'mataKuliahs', 'dosens', 'haris', 'kelasOptions'));
    }

    public function update(Request $request, PengampuMataKuliah $pengampuMataKuliah)
    {
        $this->validateJadwal($request, $pengampuMataKuliah->id);

        DB::transaction(function () use ($request, $pengampuMataKuliah) {
            $oldData = $pengampuMataKuliah->toArray();
            $pengampuMataKuliah->update($request->all());

            JadwalKuliah::where('mata_kuliah_id', $oldData['mata_kuliah_id'])
                ->where('dosen_id', $oldData['dosen_id'])
                ->where('hari', $oldData['hari'])
                ->where('jam_mulai', $oldData['jam_mulai'])
                ->update($request->except(['_token', '_method']));
        });

        return response()->json(['success' => 'Jadwal Mengajar berhasil diperbarui!']);
    }

    public function destroy(PengampuMataKuliah $pengampuMataKuliah)
    {
        DB::transaction(function () use ($pengampuMataKuliah) {
            JadwalKuliah::where('mata_kuliah_id', $pengampuMataKuliah->mata_kuliah_id)
                ->where('dosen_id', $pengampuMataKuliah->dosen_id)
                ->where('hari', $pengampuMataKuliah->hari)
                ->where('jam_mulai', $pengampuMataKuliah->jam_mulai)
                ->delete();
            $pengampuMataKuliah->delete();
        });
        
        return response()->json(['success' => 'Jadwal Mengajar berhasil dihapus.']);
    }

    private function validateJadwal(Request $request, $ignoreId = null)
    {
        $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'dosen_id' => 'required|exists:dosens,id',
            'hari' => 'required|string|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'ruangan' => 'required|string|max:100',
            'kelas' => 'required|string|max:10',
        ]);
    }
}
