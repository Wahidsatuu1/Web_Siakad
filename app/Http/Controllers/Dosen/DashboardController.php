<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Mahasiswa; 
use Illuminate\Http\Request;
use App\Models\Announcement;
use App\Models\NilaiMahasiswa;
use App\Models\PresensiMahasiswa; 
use App\Models\PengampuMataKuliah; 
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class DashboardController extends Controller
{
       public function dashboard()
    {
        $dosen = Auth::user()->dosen;
        if (!$dosen) {
            return redirect('/')->with('error', 'Data dosen tidak ditemukan untuk akun ini.');
        }

        // --- STATISTIK KARTU ---
        $totalMataKuliahDiajar = PengampuMataKuliah::where('dosen_id', $dosen->id)
                                    ->distinct('mata_kuliah_id')
                                    ->count();
        $totalJadwalMengajar = PengampuMataKuliah::where('dosen_id', $dosen->id)->count();
        $totalNilaiDiinput = NilaiMahasiswa::where('dosen_id', $dosen->id)->count();
        $kelasDiajar = PengampuMataKuliah::where('dosen_id', $dosen->id)
                                        ->pluck('kelas')
                                        ->unique();
        
        $totalMahasiswaBimbingan = Mahasiswa::whereIn('kelas', $kelasDiajar)->count();
        // =======================================================================


        // --- DATA LAIN UNTUK DASHBOARD ---
        $dayOrder = "CASE hari WHEN 'Senin' THEN 1 WHEN 'Selasa' THEN 2 WHEN 'Rabu' THEN 3 WHEN 'Kamis' THEN 4 WHEN 'Jumat' THEN 5 WHEN 'Sabtu' THEN 6 ELSE 7 END";
        $upcomingSchedules = PengampuMataKuliah::where('dosen_id', $dosen->id)
            ->with(['mataKuliah', 'dosen'])
            ->orderByRaw($dayOrder)
            ->orderBy('jam_mulai')
            ->limit(5)
            ->get();
        $recentGradesInputted = NilaiMahasiswa::where('dosen_id', $dosen->id)
            ->latest()
            ->limit(5)
            ->with(['mahasiswa', 'mataKuliah'])
            ->get();
        $announcements = Announcement::where(function ($query) {
                $query->where('target_role', 'Dosen')
                      ->orWhere('target_role', 'Semua');
            })
            ->latest()
            ->limit(5)
            ->get();

        // Mengirim semua data yang sudah dihitung ke view
        return view('dosen.dashboard', compact(
            'dosen', 
            'totalMataKuliahDiajar', 
            'totalJadwalMengajar', 
            'totalNilaiDiinput', 
            'totalMahasiswaBimbingan', // <-- Kirim data baru ke view
            'upcomingSchedules', 
            'recentGradesInputted',
            'announcements'
        ));
    }

    public function lihatJadwalMengajar()
    {
        $dosen = Auth::user()->dosen;
        if (!$dosen) {
            return redirect('/')->with('error', 'Data dosen tidak ditemukan.');
        }

        $jadwalMengajar = PengampuMataKuliah::where('dosen_id', $dosen->id)
                                         ->with(['mataKuliah', 'dosen'])
                                         ->orderBy('hari')
                                         ->orderBy('jam_mulai')
                                         ->get();

        return view('dosen.jadwal_mengajar', compact('dosen', 'jadwalMengajar'));
    }

   public function kelolaNilaiMahasiswa()
    {
        $dosen = Auth::user()->dosen;
        if (!$dosen) {
            return redirect('/')->with('error', 'Data dosen tidak ditemukan.');
        }

        // Ambil semua data nilai sebagai satu daftar (flat list), tidak perlu di-group
        $nilaiMahasiswas = NilaiMahasiswa::where('dosen_id', $dosen->id)
            ->with(['mahasiswa', 'mataKuliah'])
            ->get();

        // Kirim data dosen dan daftar nilai ke view
        return view('dosen.kelola_nilai_mahasiswa', compact('dosen', 'nilaiMahasiswas'));
    }

    public function createNilai()
    {
        $dosen = Auth::user()->dosen;
        if (!$dosen) {
            return redirect('/')->with('error', 'Data dosen tidak ditemukan.');
        }

        $mahasiswas = Mahasiswa::orderBy('nama')->get();
        $pengampuMataKuliahs = PengampuMataKuliah::where('dosen_id', $dosen->id)
                                                 ->with('mataKuliah')
                                                 ->get();

        return view('dosen.input_nilai', compact('dosen', 'mahasiswas', 'pengampuMataKuliahs'));
    }

    public function storeNilai(Request $request)
{
    $dosen = Auth::user()->dosen;

    if (!$dosen) {
        return redirect('/')->with('error', 'Data dosen tidak ditemukan.');
    }

    $pengampuMataKuliah = PengampuMataKuliah::find($request->pengampu_mata_kuliah_id);

    if (!$pengampuMataKuliah || $pengampuMataKuliah->dosen_id !== $dosen->id) {
        return redirect()->back()->withInput()->with('error', 'Mata kuliah yang dipilih tidak valid atau Anda tidak berhak menginput nilai untuk mata kuliah tersebut.');
    }

    // ================== PERBAIKAN DI SINI ==================
    $request->validate([
        'mahasiswa_id' => 'required|exists:mahasiswas,id',
        'pengampu_mata_kuliah_id' => 'required|exists:pengampu_mata_kuliah,id', // Diubah ke nama tabel yang benar
        'nilai_tugas' => 'nullable|numeric|min:0|max:100',
        'nilai_uts' => 'nullable|numeric|min:0|max:100',
        'nilai_uas' => 'nullable|numeric|min:0|max:100',
    ], [
        'mahasiswa_id.required' => 'Mahasiswa wajib dipilih.',
        'pengampu_mata_kuliah_id.required' => 'Mata kuliah wajib dipilih.',
        'pengampu_mata_kuliah_id.exists' => 'Mata kuliah yang dipilih tidak valid.',
        'nilai_tugas.numeric' => 'Nilai tugas harus berupa angka.',
        'nilai_uts.numeric' => 'Nilai UTS harus berupa angka.',
        'nilai_uas.numeric' => 'Nilai UAS harus berupa angka.',
    ]);
    // =======================================================

    $jumlahKehadiran = PresensiMahasiswa::where('mahasiswa_id', $request->mahasiswa_id)
                                        ->where('pengampu_mata_kuliah_id', $request->pengampu_mata_kuliah_id)
                                        ->where('status_kehadiran', 'Hadir')
                                        ->count();

    $existingNilai = NilaiMahasiswa::where('mahasiswa_id', $request->mahasiswa_id)
                                  ->where('mata_kuliah_id', $pengampuMataKuliah->mata_kuliah_id)
                                  ->where('kelas', $pengampuMataKuliah->kelas)
                                  ->first();

    if ($existingNilai) {
        return redirect()->back()->withInput()->with('error', 'Nilai untuk mahasiswa ini pada mata kuliah dan kelas yang sama sudah ada. Gunakan fitur edit jika ingin mengubah.');
    }

    NilaiMahasiswa::create([
        'mahasiswa_id' => $request->mahasiswa_id,
        'mata_kuliah_id' => $pengampuMataKuliah->mata_kuliah_id,
        'dosen_id' => $dosen->id,
        'kelas' => $pengampuMataKuliah->kelas,
        'kehadiran' => $jumlahKehadiran,
        'nilai_tugas' => $request->nilai_tugas,
        'nilai_uts' => $request->nilai_uts,
        'nilai_uas' => $request->nilai_uas,
        // nilai_huruf tidak perlu diisi karena akan dihitung oleh accessor di model
    ]);

    return redirect()->route('dosen.kelolaNilaiMahasiswa')->with('success', 'Nilai mahasiswa berhasil ditambahkan!');
}
      public function lihatDaftarMahasiswa(Request $request)
    {
        $dosen = Auth::user()->dosen;
        if (!$dosen) {
            return redirect('/')->with('error', 'Data dosen tidak ditemukan.');
        }

        // --- Ambil semua data yang relevan tanpa filter request ---
        // 1. Dapatkan semua kelas unik yang diajar oleh dosen ini
        $kelasDiajarOlehDosen = PengampuMataKuliah::where('dosen_id', $dosen->id)
            ->pluck('kelas')
            ->unique()
            ->sort()
            ->values();

        // 2. Dapatkan semua mahasiswa dari kelas-kelas tersebut
        $mahasiswas = Mahasiswa::whereIn('kelas', $kelasDiajarOlehDosen)
            ->orderBy('nama')
            ->get();
            
        // 3. Dapatkan semua mata kuliah yang diajar oleh dosen ini untuk opsi filter
        $mataKuliahsDiajar = PengampuMataKuliah::where('dosen_id', $dosen->id)
            ->with('mataKuliah')
            ->get()
            ->unique('mata_kuliah_id')
            ->sortBy('mataKuliah.nama_mk');

        // 4. Dapatkan status presensi hari ini (jika ada)
        $tanggalHariIni = Carbon::today()->toDateString();
        $pengampuIdsHariIni = PengampuMataKuliah::where('dosen_id', $dosen->id)
            ->where('hari', Carbon::now()->locale('id')->isoFormat('dddd'))
            ->pluck('id');
            
        $presensiStatusHariIni = collect();
        if ($pengampuIdsHariIni->isNotEmpty() && $mahasiswas->isNotEmpty()) {
            $presensiStatusHariIni = PresensiMahasiswa::whereIn('mahasiswa_id', $mahasiswas->pluck('id'))
                ->whereIn('pengampu_mata_kuliah_id', $pengampuIdsHariIni)
                ->whereDate('tanggal', $tanggalHariIni)
                ->where('status_kehadiran', 'Hadir')
                ->pluck('mahasiswa_id') // Hanya butuh ID mahasiswa yang hadir
                ->flip(); // `flip` untuk pengecekan cepat menggunakan `isset()`
        }

        return view('dosen.daftar_mahasiswa', compact(
            'dosen',
            'mahasiswas',
            'kelasDiajarOlehDosen', // Kirim daftar kelas untuk filter
            'mataKuliahsDiajar',    // Kirim daftar MK untuk filter
            'presensiStatusHariIni'
        ));
    }

    public function togglePresensiHarian(Request $request)
    {
        try {
            $dosen = Auth::user()->dosen;
            if (!$dosen) {
                return response()->json(['success' => false, 'message' => 'Sesi tidak valid atau data dosen tidak ditemukan.'], 401);
            }

            $validatedData = $request->validate([
                'mahasiswa_id' => 'required|exists:mahasiswas,id',
                'is_present' => 'required|boolean',
            ]);

            $mahasiswaId = $validatedData['mahasiswa_id'];
            $isPresent = $validatedData['is_present'];
            $tanggalHariIni = Carbon::today()->toDateString();
            $hariIniCarbon = Carbon::now()->locale('id')->isoFormat('dddd'); // Gunakan nama hari dalam Bahasa Indonesia

            $mahasiswa = Mahasiswa::find($mahasiswaId);
            if (!$mahasiswa) {
                // Sebenarnya sudah divalidasi oleh 'exists:mahasiswas,id', tapi double check tidak masalah
                return response()->json(['success' => false, 'message' => 'Mahasiswa tidak ditemukan.'], 404);
            }

            // Dapatkan semua ID Pengampu Mata Kuliah yang diajar oleh dosen ini,
            // untuk kelas mahasiswa tersebut, pada hari ini.
            // Ini penting karena satu mahasiswa bisa punya beberapa jadwal dengan dosen yang sama di hari yang sama jika dosen mengajar >1 MK di kelas itu.
            $pengampuJadwalHariIniUntukMahasiswa = PengampuMataKuliah::where('dosen_id', $dosen->id)
                ->where('kelas', $mahasiswa->kelas) // Filter berdasarkan kelas mahasiswa
                ->where('hari', $hariIniCarbon)     // Filter berdasarkan hari ini
                ->pluck('id');

            if ($pengampuJadwalHariIniUntukMahasiswa->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'Tidak ada jadwal mata kuliah aktif yang Anda ampu untuk kelas mahasiswa ini pada hari ini.'], 404);
            }

            // Hapus presensi yang mungkin sudah ada untuk mahasiswa ini pada semua jadwal relevan hari ini
            PresensiMahasiswa::where('mahasiswa_id', $mahasiswaId)
                ->whereIn('pengampu_mata_kuliah_id', $pengampuJadwalHariIniUntukMahasiswa)
                ->whereDate('tanggal', $tanggalHariIni)
                ->delete();
$statusKehadiranResponse = $isPresent ? 'Hadir' : 'Tidak Hadir'; // Status untuk frontend

if ($isPresent) {
    // Jika hadir, buat record presensi untuk setiap jadwal yang relevan
    $presensiToInsert = [];
    $now = Carbon::now();
    foreach ($pengampuJadwalHariIniUntukMahasiswa as $pengampuId) {
        $presensiToInsert[] = [
            'mahasiswa_id' => $mahasiswaId,
            'pengampu_mata_kuliah_id' => $pengampuId,
            'tanggal' => $tanggalHariIni,
            'waktu_presensi' => $now->toTimeString(),
            'status_kehadiran' => 'Hadir', // Langsung set 'Hadir'
            'created_at' => $now,
            'updated_at' => $now,
        ];
    }
    if (!empty($presensiToInsert)) {
        PresensiMahasiswa::insert($presensiToInsert);
    }
}
// Jika tidak isPresent, record presensi 'Hadir' sudah dihapus di logika sebelumnya.
// Tidak ada record di database = Tidak Hadir. Ini sudah benar.

return response()->json([
    'success' => true,
    'message' => 'Presensi berhasil diperbarui!',
    'status_kehadiran' => $statusKehadiranResponse // Selalu kirim 'Hadir' atau 'Tidak Hadir'
]);
        } catch (ValidationException $e) {
            Log::error('Validation Error in togglePresensiHarian: ' . $e->getMessage(), ['errors' => $e->errors()]);
            return response()->json(['success' => false, 'message' => 'Data yang dikirim tidak valid.', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error toggling presensi: ' . $e->getMessage() . ' on line ' . $e->getLine() . ' in ' . $e->getFile(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan internal server. Silakan coba lagi nanti.'], 500);
        }
    }

    public function showChangePasswordForm()
    {
        return view('dosen.settings.change_password'); 
    }

    public function changePassword(Request $request)
    {
       
        $request->validate([
            'new_password' => 'required|string|min:8|confirmed',
        ], [
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
