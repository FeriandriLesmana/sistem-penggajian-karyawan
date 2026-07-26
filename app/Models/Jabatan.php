<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Tambahan untuk factory
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Jabatan extends Model
{
    use HasFactory;
    
    // 👇 1. Panggil trait LogsActivity di sini 👇
    use LogsActivity; 

    // Agar Filament bisa menyimpan data ke kolom-kolom ini
    protected $fillable = [
        'nama_jabatan',
        'gaji_pokok',
        'tunjangan_makan',
        'tunjangan_transport',
        'uang_lembur',
    ];

    // Relasi: Satu Jabatan bisa dimiliki banyak Karyawan
    public function karyawans()
    {
        return $this->hasMany(Karyawan::class);
    }

    // 👇 2. Tambahkan fungsi konfigurasi Log di sini 👇
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll() // Merekam semua kolom yang ada di $fillable
            ->logOnlyDirty() // Hanya merekam data yang benar-benar diubah
            ->dontSubmitEmptyLogs() // Mencegah log kosong
            ->setDescriptionForEvent(fn(string $eventName) => "Data Jabatan telah di-{$eventName}");
    }
}