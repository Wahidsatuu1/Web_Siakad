<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MataKuliahController extends Controller
{
    /**
     * Menampilkan daftar mata kuliah menggunakan DataTables.
     */
    public function index()
    {
        $mataKuliahs = MataKuliah::orderBy('nama_mk')->get();
        return view('admin.mata_kuliah.index', compact('mataKuliahs'));
    }

    /**
     * Menampilkan form untuk membuat mata kuliah baru.
     */
    public function create()
    {
        return view('admin.mata_kuliah.create');
    }

    /**
     * Menyimpan mata kuliah baru via AJAX.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_mk' => 'required|string|max:20|unique:mata_kuliahs,kode_mk',
            'nama_mk' => 'required|string|max:255',
            'sks' => 'required|integer|min:1|max:6',
            'kelas' => 'required|string|in:Reguler A,Reguler B,REGULER CK,REGULER CS',
            'deskripsi' => 'nullable|string',
        ]);

        MataKuliah::create($request->all());

        return response()->json(['success' => 'Data mata kuliah berhasil ditambahkan!']);
    }

    /**
     * Menampilkan form untuk mengedit mata kuliah.
     */
    public function edit(MataKuliah $mataKuliah)
    {
        return view('admin.mata_kuliah.edit', compact('mataKuliah'));
    }

    /**
     * Memperbarui data mata kuliah via AJAX.
     */
    public function update(Request $request, MataKuliah $mataKuliah)
    {
        $request->validate([
            'kode_mk' => ['required', 'string', 'max:20', Rule::unique('mata_kuliahs')->ignore($mataKuliah->id)],
            'nama_mk' => 'required|string|max:255',
            'sks' => 'required|integer|min:1|max:6',
            'kelas' => 'required|string|in:Reguler A,Reguler B,REGULER CK,REGULER CS',
            'deskripsi' => 'nullable|string',
        ]);

        $mataKuliah->update($request->all());

        return response()->json(['success' => 'Data mata kuliah berhasil diperbarui!']);
    }

    /**
     * Menghapus data mata kuliah via AJAX.
     */
    public function destroy(Request $request, MataKuliah $mataKuliah)
    {
        // Pengecekan relasi: contoh jika mata kuliah sudah ada di jadwal
        if ($mataKuliah->jadwalKuliah()->exists()) {
            return response()->json(['error' => 'Mata kuliah tidak dapat dihapus karena sudah memiliki jadwal.'], 422);
        }

        $mataKuliahName = $mataKuliah->nama_mk;
        $mataKuliah->delete();

        return response()->json(['success' => "Mata kuliah '$mataKuliahName' berhasil dihapus."]);
    }
}
