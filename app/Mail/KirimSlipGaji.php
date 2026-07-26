<?php

namespace App\Mail;

use App\Models\Penggajian;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class KirimSlipGaji extends Mailable
{
    use Queueable, SerializesModels;

    public $penggajian;

    public function __construct(Penggajian $penggajian)
    {
        $this->penggajian = $penggajian;
    }

public function build()
    {
        // 1. Buat PDF
        $pdf = Pdf::loadView('pdf.slip_gaji', ['record' => $this->penggajian]);

        // 2. SIAPKAN PASSWORD (4 DIGIT TERAKHIR NO HP) ✂️
        $noHp = $this->penggajian->karyawan->nomor_telepon;

        // Logika: 
        // - Kalau No HP ada, ambil 4 angka paling belakang.
        // - Kalau No HP kosong, pakai default '1234'.
        $password = $noHp ? substr($noHp, -4) : '1234';

        // 3. Pasang Enkripsi
        $pdf->setEncryption($password);

        // 4. Kirim Email
        return $this->subject('Slip Gaji Periode ' . $this->penggajian->tanggal_gajian)
                    ->view('emails.slip_gaji_body')
                    ->attachData($pdf->output(), 'Slip-Gaji.pdf');
    }
}