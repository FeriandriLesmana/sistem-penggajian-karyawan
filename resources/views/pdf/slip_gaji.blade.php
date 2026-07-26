<!DOCTYPE html>
<html>
<head>
    <title>Slip Gaji Karyawan</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; color: #333; }
        
        .header { text-align: center; margin-bottom: 30px; }
        .header h2 { margin: 0; text-transform: uppercase; }
        .header p { margin: 5px 0; color: #555; font-size: 12px; }
        .garis { border-bottom: 2px solid #000; margin-bottom: 20px; }

        .judul { text-align: center; font-weight: bold; font-size: 18px; margin-bottom: 20px; text-decoration: underline; }

        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 4px; vertical-align: top; }

        .rincian-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .rincian-table th, .rincian-table td { border: 1px solid #ccc; padding: 8px; }
        .rincian-table th { background-color: #f0f0f0; text-align: left; }
        
        .total-row td { font-weight: bold; background-color: #e6e6e6; }
        .text-right { text-align: right; }

        .footer { margin-top: 50px; width: 100%; }
        .ttd-box { width: 40%; float: right; text-align: center; }
        .ttd-space { height: 80px; }
        .note { font-size: 10px; font-style: italic; margin-top: 20px; color: #777; }
    </style>
</head>
<body>

    <div class="header">
        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/logo.png'))) }}" 
             alt="Logo Dhiarfa Acrylic" 
             style="width: 120px; height: auto; margin-bottom: 10px;">
        <h2>Dhiarfa Akrilik</h2>
        <p>25, Taman Raya Rajeg, Kabupaten Tangerang, Banten 15540</p>
        <p>Telp: 081585761321 | Email: hrd@ferisystems.com</p>
    </div>
    <div class="garis"></div>

    <div class="judul">SLIP GAJI KARYAWAN</div>

    <table class="info-table">
        <tr>
            <td width="20%"><strong>Nama Karyawan</strong></td>
            <td width="2%">:</td>
            <td>{{ $record->karyawan->nama_lengkap }}</td>
            
            <td width="20%"><strong>Periode Gaji</strong></td>
            <td width="2%">:</td>
            <td class="text-right">{{ \Carbon\Carbon::parse($record->tanggal_gajian)->format('d F Y') }}</td>
        </tr>
        <tr>
            <td><strong>Jabatan</strong></td>
            <td>:</td>
            <td>{{ $record->karyawan->jabatan->nama_jabatan }}</td>

            <td><strong>ID Transaksi</strong></td>
            <td>:</td>
            <td class="text-right">#PAY-{{ $record->id }}</td>
        </tr>
    </table>

    <table class="rincian-table">
        <thead>
            <tr>
                <th>Keterangan</th>
                <th class="text-right">Jumlah (IDR)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Gaji Pokok</td>
                <td class="text-right">{{ number_format($record->gaji_pokok, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Total Tunjangan (Makan + Transport)</td>
                <td class="text-right">{{ number_format($record->total_tunjangan, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Uang Lembur</td>
                <td class="text-right">{{ number_format($record->total_lembur, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="color: red;">Potongan (Terlambat/Absen/Lainnya)</td>
                <td class="text-right" style="color: red;">- {{ number_format($record->total_potongan, 0, ',', '.') }}</td>
            </tr>
            <tr class="total-row">
                <td>TOTAL GAJI BERSIH (TAKE HOME PAY)</td>
                <td class="text-right">Rp {{ number_format($record->gaji_bersih, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <div class="ttd-box">
            <p>Tangerang Selatan, {{ now()->format('d F Y') }}</p>
            <p>Manager Keuangan,</p>
            <img src="{{ public_path('images/ttd.png') }}" style="width: 100px; height: auto; margin: 10px 0;">
            <p><strong>( Dimas Galih )</strong></p>
        </div>
    </div>

    <div style="clear: both;"></div>
    <p class="note">* Slip gaji ini diterbitkan secara otomatis oleh sistem dan sah tanpa tanda tangan basah.</p>

</body>
</html>