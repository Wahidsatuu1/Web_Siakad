<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AbsenDosen;
use Carbon\Carbon;

class AbsenDosenController extends Controller
{
    public function index()
    {
        $dosen = Auth::user()->dosen; 
        if (!$dosen) {
            return redirect()->back()->with('error', 'Data dosen tidak ditemukan.');
        }

        $today = Carbon::today();
        $absenToday = AbsenDosen::where('dosen_id', $dosen->id)
                                ->whereDate('tanggal', $today)
                                ->first();

        $riwayatAbsen = AbsenDosen::where('dosen_id', $dosen->id)
                                  ->where('tanggal', '<=', $today)
                                  ->orderBy('tanggal', 'desc')
                                  ->limit(7) // Batasi riwayat
                                  ->get();

        return view('dosen.absen.index', compact('absenToday', 'riwayatAbsen'));
    }

    public function checkIn(Request $request)
    {
        $dosen = Auth::user()->dosen;
        if (!$dosen) {
            return redirect()->back()->with('error', 'Data dosen tidak ditemukan.');
        }

        $today = Carbon::today();

        $absenToday = AbsenDosen::where('dosen_id', $dosen->id)
                                ->whereDate('tanggal', $today)
                                ->first();

        if ($absenToday) {
            return redirect()->back()->with('error', 'Anda sudah melakukan absen masuk hari ini.');
        }

        AbsenDosen::create([
            'dosen_id' => $dosen->id,
            'tanggal' => $today,
            'waktu_masuk' => Carbon::now()->format('H:i'),
            'status' => 'Hadir', 
            'keterangan' => 'Absen masuk otomatis',
        ]);

        return redirect()->back()->with('success', 'Absen masuk berhasil dicatat!');
    }

    public function checkOut(Request $request)
    {
        $dosen = Auth::user()->dosen;
        if (!$dosen) {
            return redirect()->back()->with('error', 'Data dosen tidak ditemukan.');
        }

        $today = Carbon::today();

        $absenToday = AbsenDosen::where('dosen_id', $dosen->id)
                                ->whereDate('tanggal', $today)
                                ->whereNotNull('waktu_masuk')
                                ->first();

        if (!$absenToday) {
            return redirect()->back()->with('error', 'Anda belum melakukan absen masuk hari ini.');
        }

        if ($absenToday->waktu_keluar) {
            return redirect()->back()->with('error', 'Anda sudah melakukan absen keluar hari ini.');
        }

        $absenToday->update([
            'waktu_keluar' => Carbon::now()->format('H:i'),
            'keterangan' => 'Absen masuk & keluar otomatis', 
        ]);

        return redirect()->back()->with('success', 'Absen keluar berhasil dicatat!');
    }
}