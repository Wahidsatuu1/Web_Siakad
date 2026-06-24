<?php

namespace App\Http\Controllers;

use App\Models\NilaiMahasiswa;
use App\Models\PengampuMataKuliah;
use App\Models\PresensiMahasiswa;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // Keep for changePassword
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule; // Required for unique email validation
use App\Models\Krs;
use Barryvdh\DomPDF\Facade\Pdf; // Library DomPDF
use Maatwebsite\Excel\Facades\Excel; // Library Excel
use App\Exports\KrsExport; // File export excel yang akan dibuat
use Illuminate\Support\Facades\Storage; // Untuk penyimpanan file foto

class MahasiswaController extends Controller
{
    public function dashboard()
    {
        $mahasiswa = Auth::user()->mahasiswa;

        if (!$mahasiswa) {
            return redirect('/')->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        // 1. Ambil Data KRS & Hitung Total SKS Semester Ini
        $totalSksKrs = 0;
        if ($mahasiswa->kelas) {
            $krsDetails = PengampuMataKuliah::where('kelas', $mahasiswa->kelas)
                ->with('mataKuliah')
                ->get();
            $totalSksKrs = $krsDetails->sum(fn($item) => optional($item->mataKuliah)->sks ?? 0);
        }

        // 2. Ambil Jadwal Kuliah Hari Ini
        $upcomingClasses = collect();
        if ($mahasiswa->kelas) {
            $upcomingClasses = PengampuMataKuliah::where('kelas', $mahasiswa->kelas)
                ->where('hari', Carbon::now()->locale('id')->isoFormat('dddd'))
                ->orderBy('jam_mulai')
                ->with(['mataKuliah', 'dosen'])
                ->get();
        }

        // 3. Ambil Semua Nilai dengan Relasi Mata Kuliah (PENTING untuk IPK)
        $nilaiMahasiswas = NilaiMahasiswa::where('mahasiswa_id', $mahasiswa->id)
            ->with('mataKuliah') // Wajib ada agar accessor sks_x_mutu jalan
            ->get();

        $recentGrades = $nilaiMahasiswas->take(5);

        // 4. Kalkulasi Grafik IPS & IPK
        $ipsData = [];
        $ipkData = [];
        $totalSKSKumulatif = 0;
        $totalMutuKaliSKSKumulatif = 0;

        // Kelompokkan berdasarkan kolom 'semester' atau 'kelas'
        $nilaiPerSemester = $nilaiMahasiswas->groupBy('semester'); // Gunakan 'semester' agar grafik urut
        
        foreach ($nilaiPerSemester as $sem => $nilaiList) {
            $totalSKSPerSem = $nilaiList->sum(fn($n) => optional($n->mataKuliah)->sks ?? 0);
            $totalMutuPerSem = $nilaiList->sum('sks_x_mutu'); // Menggunakan accessor dari Model
            
            $ips = ($totalSKSPerSem > 0) ? round($totalMutuPerSem / $totalSKSPerSem, 2) : 0.00;
            $ipsData[$sem] = $ips;
            
            $totalSKSKumulatif += $totalSKSPerSem;
            $totalMutuKaliSKSKumulatif += $totalMutuPerSem;
            $ipk = ($totalSKSKumulatif > 0) ? round($totalMutuKaliSKSKumulatif / $totalSKSKumulatif, 2) : 0.00;
            $ipkData[$sem] = $ipk;
        }

        $chartLabels = array_keys($ipsData);
        $chartIPSValues = array_values($ipsData);
        $chartIPKValues = array_values($ipkData);

        // 5. Ambil Data Pengumuman
        $announcements = Announcement::where(function ($query) {
            $query->where('target_role', 'Mahasiswa')->orWhere('target_role', 'Semua');
        })->latest()->limit(5)->get();
        
        return view('mahasiswa.dashboard', compact(
            'mahasiswa', 'totalSksKrs', 'upcomingClasses', 'recentGrades',
            'chartLabels', 'chartIPSValues', 'chartIPKValues', 'announcements'
        ));
    }


    public function lihatJadwalKuliah()
    {
        $mahasiswa = Auth::user()->mahasiswa;

        if (!$mahasiswa) {
            return redirect('/')->with('error', 'Data mahasiswa tidak ditemukan.');
        }
        
        $jadwalKuliahs = collect(); // Default ke koleksi kosong
        if ($mahasiswa->kelas) {
            // --- INI BAGIAN YANG DIUBAH ---
            // Mengambil data dan langsung mengurutkannya di database
            $jadwalKuliahs = PengampuMataKuliah::where('kelas', $mahasiswa->kelas)
                ->with(['mataKuliah', 'dosen'])
                ->orderBy(DB::raw("CASE hari WHEN 'Senin' THEN 1 WHEN 'Selasa' THEN 2 WHEN 'Rabu' THEN 3 WHEN 'Kamis' THEN 4 WHEN 'Jumat' THEN 5 WHEN 'Sabtu' THEN 6 ELSE 7 END"))
                ->orderBy('jam_mulai', 'asc')
                ->get();
        }

        // Variabel yang dikirim ke view sekarang kita namakan $jadwalKuliahs agar konsisten
        return view('mahasiswa.jadwal_kuliah', compact('jadwalKuliahs', 'mahasiswa'));
    }
    public function lihatKHS()
    {
        $mahasiswa = Auth::user()->mahasiswa;

        if (!$mahasiswa) {
            return redirect('/')->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        // Ambil semua data nilai sebagai satu daftar (flat list)
        // dan urutkan langsung dari database agar efisien.
        $nilaiMahasiswas = NilaiMahasiswa::where('nilai_mahasiswas.mahasiswa_id', $mahasiswa->id)
            ->with('mataKuliah')
            ->leftJoin('mata_kuliahs', 'nilai_mahasiswas.mata_kuliah_id', '=', 'mata_kuliahs.id')
            ->orderBy('nilai_mahasiswas.kelas', 'asc') // Urutkan berdasarkan kelas/semester
            ->orderBy('mata_kuliahs.nama_mk', 'asc')   // Lalu urutkan berdasarkan nama MK
            ->select('nilai_mahasiswas.*')
            ->get();
        
        // Kirim data mahasiswa dan daftar nilainya ke view
        return view('mahasiswa.khs', compact('mahasiswa', 'nilaiMahasiswas'));
    }

    public function lihatKRS()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        if (!$mahasiswa) return redirect()->route('mahasiswa.dashboard');

        // 1. Ambil data KRS milik mahasiswa ini (untuk tabel bawah)
        $krsDetails = Krs::where('nbi', $mahasiswa->nim)
        ->with('mataKuliah') 
        ->get();
        
        // 2. Ambil SEMUA mata kuliah dari database Admin (untuk pilihan input)
        // Asumsi nama model Anda adalah MataKuliah
        $mataKuliahDasar = \App\Models\MataKuliah::all(); 

        $totalSKS = $krsDetails->sum('sks');

        return view('mahasiswa.krs', compact('mahasiswa', 'krsDetails', 'totalSKS', 'mataKuliahDasar'));
    }

    public function storeKrs(Request $request)
    {
        $mahasiswa = Auth::user()->mahasiswa;

        // Pastikan NBI yang masuk adalah NBI mahasiswa yang sedang login
        Krs::create([
            'nbi'         => $mahasiswa->nim, // Ini harus mengambil dari data mahasiswa login
            'tahun_ajaran'=> '2025/2026',
            'semester'    => 'Ganjil',
            'kode_mk'     => $request->kodemk,
            'kelas'       => 'R', // Berikan default atau ambil dari input
            'sks'         => $request->sks,
            'kode_dsn'    => $request->kodedsn,
        ]);

        return redirect()->back()->with('success', 'Mata kuliah berhasil ditambahkan!');
    }

    public function destroyKrs($id)
    {
        $mahasiswa = Auth::user()->mahasiswa;
        
        // Perbaikan: Gunakan $mahasiswa->nim
        $krs = Krs::where('id', $id)->where('nbi', $mahasiswa->nim)->firstOrFail();
        $krs->delete();

        return redirect()->back()->with('success', 'Mata kuliah berhasil dihapus dari KRS Anda.');
    }

    public function lihatDetailPribadi()
    {
        $mahasiswa = Auth::user()->mahasiswa;

        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Data pribadi tidak ditemukan.');
        }
        // Eager load user relation if email is on user table
        $mahasiswa->load('user'); 

        return view('mahasiswa.detail_pribadi', compact('mahasiswa'));
    }
   public function lihatRangkumanNilai()
    {
        $mahasiswa = Auth::user()->mahasiswa;

        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $nilaiMahasiswas = NilaiMahasiswa::where('mahasiswa_id', $mahasiswa->id)
            ->with('mataKuliah')
            ->get()->sortBy(function($nilai) {
                return $nilai->kelas . optional($nilai->mataKuliah)->nama_mk;
            });
            
        // Lakukan kalkulasi total SKS dan total Mutu di sini
        $totalSKSKumulatif = 0;
        $totalMutuKumulatif = 0; // Variabel baru untuk Total Mutu

        foreach ($nilaiMahasiswas as $nilai) {
            $totalSKSKumulatif += optional($nilai->mataKuliah)->sks ?? 0;
            // Panggil accessor baru sks_x_mutu yang sudah kita buat
            $totalMutuKumulatif += $nilai->sks_x_mutu; 
        }
        
        $ipkKumulatif = ($totalSKSKumulatif > 0) ? round($totalMutuKumulatif / $totalSKSKumulatif, 2) : 0.00;
        
        return view('mahasiswa.rangkuman_nilai', compact('mahasiswa', 'nilaiMahasiswas', 'ipkKumulatif', 'totalSKSKumulatif', 'totalMutuKumulatif'));
    }

     public function showPresensiForm()
    {
        $mahasiswa = Auth::user()->mahasiswa;

        if (!$mahasiswa) {
            Log::warning('Attempt to access presensi form without mahasiswa data for user ID: ' . Auth::id());
            return redirect('/')->with('error', 'Data mahasiswa tidak ditemukan untuk akun ini. Harap hubungi administrasi.');
        }

        $hariIniCarbon = Carbon::now()->locale('id');
        $hariIni = $hariIniCarbon->isoFormat('dddd');
        $tanggalHariIni = $hariIniCarbon->toDateString();

        $jadwalHariIni = PengampuMataKuliah::where('kelas', $mahasiswa->kelas)
            ->where('hari', $hariIni)
            ->with(['mataKuliah', 'dosen']) 
            ->orderBy('jam_mulai')
            ->get();

        $presensiSudahDilakukan = collect(); 
        if ($jadwalHariIni->isNotEmpty()) {
            $presensiSudahDilakukan = PresensiMahasiswa::where('mahasiswa_id', $mahasiswa->id)
                ->whereDate('tanggal', $tanggalHariIni)
                ->whereIn('pengampu_mata_kuliah_id', $jadwalHariIni->pluck('id'))
                ->get() 
                ->keyBy('pengampu_mata_kuliah_id'); 
        }
        
        return view('mahasiswa.presensi', compact('mahasiswa', 'jadwalHariIni', 'hariIni', 'presensiSudahDilakukan'));
    }

    public function submitPresensi(Request $request)
    {
        $mahasiswa = Auth::user()->mahasiswa;

        if (!$mahasiswa) {
            return response()->json(['success' => false, 'message' => 'Sesi tidak valid atau data mahasiswa tidak ditemukan.'], 401);
        }

        try {
            $validatedData = $request->validate([
                'pengampu_mata_kuliah_id' => 'required|exists:pengampu_mata_kuliah,id', 
                'status_kehadiran' => 'required|string|in:Hadir,Tidak Hadir',
            ]);

            $pengampuMataKuliahId = $validatedData['pengampu_mata_kuliah_id'];
            $statusKehadiranInput = $validatedData['status_kehadiran']; 
            $tanggalPresensi = Carbon::today()->toDateString();

            $jadwal = PengampuMataKuliah::where('id', $pengampuMataKuliahId)
                                        ->where('kelas', $mahasiswa->kelas)
                                        ->where('hari', Carbon::now()->locale('id')->isoFormat('dddd'))
                                        ->first();
            if (!$jadwal) {
                return response()->json(['success' => false, 'message' => 'Jadwal tidak valid atau sudah lewat untuk Anda.'], 403);
            }
            
            $statusKehadiranDB = $statusKehadiranInput; 


            PresensiMahasiswa::updateOrCreate(
                [
                    'mahasiswa_id' => $mahasiswa->id,
                    'pengampu_mata_kuliah_id' => $pengampuMataKuliahId,
                    'tanggal' => $tanggalPresensi,
                ],
                [
                    'waktu_presensi' => Carbon::now()->toTimeString(),
                    'status_kehadiran' => $statusKehadiranDB, 
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Presensi berhasil dicatat sebagai: ' . $statusKehadiranInput, 
                'status_kehadiran_display' => $statusKehadiranInput, 
                'pengampu_mata_kuliah_id' => $pengampuMataKuliahId
            ]);

        } catch (ValidationException $e) {
            Log::error('Validation Error for Mahasiswa Presensi (Toggle): ' . $e->getMessage(), ['errors' => $e->errors()]);
            $errorMessages = [];
            foreach($e->errors() as $field => $messages) {
                $errorMessages[] = $messages[0];
            }
            return response()->json(['success' => false, 'message' => implode(' ', $errorMessages), 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error submitting presensi for Mahasiswa (Toggle): ' . $e->getMessage() . ' Line: ' . $e->getLine() . ' File: ' . $e->getFile());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan internal server. Silakan coba lagi nanti.'], 500);
        }
    }

    public function editDetailPribadi()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Data mahasiswa tidak ditemukan.');
        }
        $mahasiswa->load('user'); // Eager load user untuk akses email dan nama jika di tabel user
        return view('mahasiswa.edit_detail_pribadi', compact('mahasiswa'));
    }


    public function updateDetailPribadi(Request $request)
    {
        // 1. Ambil data mahasiswa yang sedang login
        $mahasiswa = Auth::user()->mahasiswa;

        // 2. Validasi input
        $request->validate([
            'nama'   => 'required|string|max:255',
            'email'  => 'required|email|max:255',
            'foto'   => 'nullable|image|mimes:jpg,png,jpeg|max:2048', // Batasi maksimal 2MB
        ]);

        // 3. Update data di tabel Users (Nama & Email)
        $mahasiswa->user->update([
            'name'  => $request->nama,
            'email' => $request->email
        ]);

        // 4. Update data di tabel Mahasiswas (Telepon, Alamat, dll)
        $mahasiswa->telepon = $request->telepon;
        $mahasiswa->tanggal_lahir = $request->tanggal_lahir;
        $mahasiswa->alamat = $request->alamat;

        // --- TARUH KODE UPLOAD FOTO DI SINI ---
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada untuk menghemat penyimpanan
            if ($mahasiswa->foto) {
                Storage::disk('public')->delete($mahasiswa->foto);
            }
            // Simpan foto baru ke folder public/uploads/profile
            $path = $request->file('foto')->store('uploads/profile', 'public');
            $mahasiswa->foto = $path;
        }
        // ---------------------------------------

        // 5. Simpan semua perubahan ke database
        $mahasiswa->save();

        return redirect()->route('mahasiswa.profil.detail')->with('success', 'Profil berhasil diperbarui!');
    }
    // --- Akhir Metode Baru ---


    public function showChangePasswordForm()
    {
        return view('mahasiswa.settings.change_password');
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
        $user->password = Hash::make($request->new_password); // Menggunakan Hash::make()
        $user->save();

        return redirect()->back()->with('success', 'Password berhasil diubah!');
    }

    public function exportPdf()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $krsDetails = Krs::where('nbi', $mahasiswa->nim)->with('mataKuliah')->get();
        $totalSKS = $krsDetails->sum('sks');
        
        // Set zona waktu agar tanggal akurat
        date_default_timezone_set('Asia/Jakarta');
        // Format: Tanggal Bulan Tahun Jam:Menit
        $tglCetak = date('d/m/Y H:i'); 

        $pdf = Pdf::loadView('mahasiswa.pdf_krs', compact('mahasiswa', 'krsDetails', 'totalSKS', 'tglCetak'));
        return $pdf->setPaper('a4', 'portrait')->stream('KRS_'.$mahasiswa->nim.'.pdf');
    }

    public function exportXls()
    {
        $nim = Auth::user()->mahasiswa->nim;
        return Excel::download(new KrsExport($nim), 'KRS_'.$nim.'.xlsx');
    }
}
