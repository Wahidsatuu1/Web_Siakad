<?php

namespace App\Imports;

use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class MahasiswasImport implements ToCollection, WithHeadingRow, WithValidation, WithChunkReading
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        // Menggunakan transaction agar jika ada 1 baris yang gagal, semua data dari file excel akan di-rollback
        DB::transaction(function () use ($rows) {
            foreach ($rows as $row) {
                // 1. Buat Akun User terlebih dahulu
                $user = User::create([
                    'name' => $row['nama'],
                    'email' => $row['email'],
                    'password' => Hash::make($row['password']),
                    'role' => 'mahasiswa',
                ]);

                // 2. Buat Data Mahasiswa yang berelasi dengan User
                $user->mahasiswa()->create([
                    'nim'               => $row['nim'],
                    'nama'              => $row['nama'],
                    'email'             => $row['email'],
                    'jurusan'           => $row['jurusan'],
                    'angkatan'          => $row['angkatan'],
                    'kelas'             => $row['kelas'],
                    'status_mahasiswa'  => $row['status_mahasiswa'],
                ]);
            }
        });
    }

    // Tentukan aturan validasi untuk setiap kolom di Excel
    public function rules(): array
    {
        return [
            'nim' => 'required|string|max:20|unique:mahasiswas,nim',
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email|unique:mahasiswas,email',
            'password' => 'required|string|min:8',
            'jurusan' => 'required|string|max:100',
            'angkatan' => 'required|digits:4',
            'status_mahasiswa' => 'required|string',
            'kelas' => 'nullable|string',
        ];
    }

    // Tentukan pesan error kustom jika validasi gagal
    public function customValidationMessages()
    {
        return [
            'nim.unique' => 'NIM sudah terdaftar di sistem.',
            'email.unique' => 'Email sudah terdaftar di sistem.',
            '*.required' => 'Kolom :attribute wajib diisi.',
            'angkatan.digits' => 'Kolom angkatan harus berupa angka 4 digit.',
        ];
    }
    
    // Optimasi untuk file besar
    public function chunkSize(): int
    {
        return 200; // Proses 200 baris sekali jalan
    }
}