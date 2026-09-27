<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Facades\Hash; // Sudah benar di sini

class Karyawan extends Model
{
    use HasFactory, LogsActivity;

    // Buka semua kolom agar NIK, Email, & Alamat bisa disimpan
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    // Relasi: Karyawan memiliki satu Jabatan
    public function jabatan(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class);
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

protected static function booted()
    {
        // 1. Event CREATING (Berjalan SEBELUM data tersimpan ke tabel Karyawan)
        static::creating(function ($karyawan) {
            // Ubah nama jadi huruf kecil semua dan hapus spasi 
            // Contoh: "Slamet Santoso" menjadi "slametsantoso"
            $formatNama = strtolower(str_replace(' ', '', $karyawan->nama_lengkap));
            
            // Paksa/timpa isian email menjadi email perusahaan
            $karyawan->email = $formatNama . '@dhiarfa.com';
        });

        // 2. Event CREATED (Berjalan SESUDAH data tersimpan ke tabel Karyawan)
        static::created(function ($karyawan) {
            // Ambil lagi format nama tanpa spasi untuk dijadikan password
            $formatNama = strtolower(str_replace(' ', '', $karyawan->nama_lengkap));

            // Sistem otomatis membuat akun di tabel Users
            $user = \App\Models\User::create([
                'name' => $karyawan->nama_lengkap,
                'email' => $karyawan->email, // Mengambil email @dhiarfa.com dari tahap 1
                'password' => Hash::make($formatNama), // Password menggunakan nama tanpa spasi
            ]);

            // Sistem otomatis memberikan atribut Role 'Karyawan'
            $user->assignRole('Karyawan');
        });
    }

    // Konfigurasi Log Aktivitas (Spatie)
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logUnguarded() // Merekam semua kolom karena menggunakan $guarded = []
            ->logOnlyDirty() // Hanya merekam data yang benar-benar diubah saja
            ->dontSubmitEmptyLogs() // Mencegah pembuatan log jika tidak ada perubahan
            ->setDescriptionForEvent(fn(string $eventName) => "Data Karyawan telah di-{$eventName}");
    }
}