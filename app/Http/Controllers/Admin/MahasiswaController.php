<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use App\Imports\MahasiswasImport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

class MahasiswaController extends Controller
{
    /**
     * Menampilkan daftar mahasiswa dengan DataTables.
     */
    public function index()
    {
        $mahasiswas = Mahasiswa::with('user')->orderBy('nama')->get();
        return view('admin.mahasiswa.index', compact('mahasiswas'));
    }

    /**
     * Menampilkan form untuk membuat mahasiswa baru.
     */
    public function create()
    {
        // Opsi untuk dropdown, bisa Anda ambil dari database atau definisikan di sini
        $programStudiOptions = ['Teknik Informatika', 'Sistem Informasi', 'Manajemen', 'Akuntansi'];
        $progPerkuliahanOptions = ['Reguler A', 'Reguler B', 'REGULER CK', 'REGULER CS'];
        $kelasOptions = ['06TPLP001', '06TPLP002', '06TPLP003', '06TPLP004', '06TPLP005', '06TPLP006', '06TPLP007', '06TPLP008', '06TPLP009', '06TPLP0010'];
        $statusMahasiswaOptions = ['Aktif', 'Cuti', 'Lulus', 'Non-Aktif', 'Drop Out'];
        return view('admin.mahasiswa.create', compact('programStudiOptions', 'progPerkuliahanOptions', 'kelasOptions', 'statusMahasiswaOptions'));
    }

    /**
     * Menyimpan data mahasiswa baru via AJAX.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nim' => 'required|string|max:20|unique:mahasiswas,nim',
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email|unique:mahasiswas,email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'jurusan' => 'required|string|max:100',
            'angkatan' => 'required|string|max:4',
            'status_mahasiswa' => 'required|string',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $user = User::create([
                    'name' => $request->nama,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role' => 'mahasiswa',
                ]);
                
                $user->mahasiswa()->create($request->except(['password', 'password_confirmation']));
            });
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menambahkan mahasiswa: ' . $e->getMessage()], 500);
        }

        return response()->json(['success' => 'Data mahasiswa dan akun login berhasil ditambahkan!']);
    }

    /**
     * Menampilkan form edit mahasiswa.
     */
    public function edit(Mahasiswa $mahasiswa)
    {
        $mahasiswa->load('user');
        $programStudiOptions = ['Teknik Informatika', 'Sistem Informasi', 'Manajemen', 'Akuntansi'];
        $progPerkuliahanOptions = ['Reguler A', 'Reguler B', 'REGULER CK', 'REGULER CS'];
        $kelasOptions = ['06TPLP001', '06TPLP002', '06TPLP003', '06TPLP004', '06TPLP005', '06TPLP006', '06TPLP007', '06TPLP008', '06TPLP009', '06TPLP0010'];
        $statusMahasiswaOptions = ['Aktif', 'Cuti', 'Lulus', 'Non-Aktif', 'Drop Out'];

        return view('admin.mahasiswa.edit', compact('mahasiswa', 'programStudiOptions', 'progPerkuliahanOptions', 'kelasOptions', 'statusMahasiswaOptions'));
    }

    /**
     * Memperbarui data mahasiswa via AJAX.
     */
    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $user = $mahasiswa->user;
        $request->validate([
            'nim' => ['required', 'string', 'max:20', Rule::unique('mahasiswas')->ignore($mahasiswa->id)],
            'nama' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id), Rule::unique('mahasiswas')->ignore($mahasiswa->id)],
            'jurusan' => 'required|string|max:100',
            'angkatan' => 'required|string|max:4',
            'status_mahasiswa' => 'required|string',
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);
        
        try {
            DB::transaction(function () use ($request, $mahasiswa, $user) {
                $mahasiswa->update($request->except(['email', 'password', 'password_confirmation']));
                
                $userData = ['name' => $request->nama, 'email' => $request->email];
                if ($request->filled('password')) {
                    $userData['password'] = Hash::make($request->password);
                }
                $user->update($userData);
            });
        } catch (\Exception $e) {
             return response()->json(['error' => 'Gagal memperbarui data mahasiswa: ' . $e->getMessage()], 500);
        }

        return response()->json(['success' => 'Data mahasiswa berhasil diperbarui!']);
    }

    /**
     * Menghapus data mahasiswa via AJAX.
     */
    public function destroy(Mahasiswa $mahasiswa)
    {
        // Tambahkan pengecekan relasi lain jika perlu, misal nilai, krs, dll.
        // if ($mahasiswa->nilai()->exists()) {
        //     return response()->json(['error' => 'Mahasiswa tidak dapat dihapus karena memiliki riwayat nilai.'], 422);
        // }

        try {
            DB::transaction(function () use ($mahasiswa) {
                $mahasiswa->user()->delete(); // Hapus user, dan profil mahasiswa akan terhapus otomatis via cascade
            });
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menghapus data: ' . $e->getMessage()], 500);
        }

        return response()->json(['success' => 'Data mahasiswa berhasil dihapus.']);
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new MahasiswasImport, $request->file('file'));
        } catch (ValidationException $e) {
            $failures = $e->failures();
            $errorRows = [];
            foreach ($failures as $failure) {
                // Mengumpulkan pesan error berdasarkan baris
                $errorRows[$failure->row()][] = $failure->errors()[0];
            }

            $errorMessages = "Impor Gagal. Terdapat kesalahan pada file Anda:\n\n";
            foreach($errorRows as $row => $messages) {
                $errorMessages .= "Baris " . $row . ": " . implode(', ', $messages) . "\n";
            }
            // Menggunakan back() agar bisa menampilkan error di session
            return back()->withErrors(['import_error' => $errorMessages]);
        } catch (\Exception $e) {
            return back()->withErrors(['import_error' => 'Terjadi kesalahan sistem: ' . $e->getMessage()]);
        }

        return redirect()->route('admin.mahasiswas.index')->with('success', 'Data mahasiswa berhasil diimpor!');
    }


    /**
     * Men-download template file Excel untuk impor.
     */
    public function downloadTemplate()
    {
        $path = public_path('templates/template_mahasiswa.xlsx');
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];

        // Buat file template jika belum ada
        if (!file_exists($path)) {
            // Buat direktori jika belum ada
            if(!is_dir(public_path('templates'))) {
                mkdir(public_path('templates'), 0755, true);
            }
            // Buat file excel sederhana dengan header
            $header = [
                'nim', 'nama', 'email', 'password', 'jurusan', 'angkatan', 'kelas', 'status_mahasiswa'
            ];
            $data = collect([$header]);
            Excel::store($data, 'public/templates/template_mahasiswa.xlsx');
            // path di storage
            $path = storage_path('app/public/templates/template_mahasiswa.xlsx');
        }


        return response()->download($path, 'template_mahasiswa.xlsx', $headers);
    }
}