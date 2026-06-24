<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 11pt; }
        .header { border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .logo { width: 70px; float: left; }
        .title { text-align: center; margin-right: 70px; }
        .footer { position: fixed; bottom: -30px; left: 0; right: 0; font-size: 9pt; border-top: 1px solid #ccc; padding-top: 5px; }
        .pagenum:before { content: counter(page); }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <div class="header">
        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/untag.png'))) }}" class="logo">
        <div class="title">
            <h2 style="margin:0;">UNIVERSITAS 17 AGUSTUS 1945   </h2>
            <p style="margin:0;">KARTU RENCANA STUDI (KRS)</p>
        </div>
        <div style="clear: both;"></div>
    </div>

    <p>Dicetak pada: <strong>{{ $tglCetak }}</strong></p>
    <p>Nama: {{ Auth::user()->name }} | NIM: {{ $mahasiswa->nim }}</p>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Kode MK</th>
            <th>Mata Kuliah</th>
            <th>SKS</th>
        </tr>
    </thead>
    <tbody>
        @foreach($krsDetails as $idx => $item)
        <tr>
            <td>{{ $idx + 1 }}</td>
            <td>{{ $item->kode_mk }}</td>
            <td>{{ $item->mataKuliah->nama_mk ?? '-' }}</td>
            <td>{{ $item->sks }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" style="text-align: right; font-weight: bold;">Total SKS yang Diambil:</td>
            <td style="font-weight: bold;">{{ $totalSKS }}</td>
        </tr>
    </tfoot>
</table>

    <div class="footer">
        Dicetak otomatis oleh Sistem Akademik - Halaman <span class="pagenum"></span>
    </div>
</body>
</html>