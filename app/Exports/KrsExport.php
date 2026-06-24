<?php
namespace App\Exports;

use App\Models\Krs;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class KrsExport implements FromCollection, WithHeadings
{
    protected $nim;

    public function __construct($nim) {
        $this->nim = $nim;
    }

    public function collection() {
        return Krs::where('nbi', $this->nim)
            ->select('kode_mk', 'sks', 'kode_dsn', 'kelas')
            ->get();
    }

    public function headings(): array {
        return ["Kode Mata Kuliah", "SKS", "ID Dosen", "Kelas"];
    }
}