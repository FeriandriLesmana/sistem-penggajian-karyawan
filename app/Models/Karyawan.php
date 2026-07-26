<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

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