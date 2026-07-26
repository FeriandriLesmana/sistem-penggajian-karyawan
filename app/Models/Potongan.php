<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
// 👇 Tambahan untuk memanggil fitur Log Aktivitas 👇
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Potongan extends Model
{
    // 👇 Tambahkan trait LogsActivity di sini 👇
    use HasFactory, LogsActivity;
    
    protected $guarded = [];

    public function karyawan(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class);
    }

    // 👇 Tambahkan fungsi konfigurasi Log di sini 👇
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logUnguarded() // Merekam semua kolom karena menggunakan $guarded = []
            ->logOnlyDirty() // Hanya merekam data yang benar-benar diubah saja
            ->dontSubmitEmptyLogs() // Mencegah pembuatan log kosong
            ->setDescriptionForEvent(fn(string $eventName) => "Data Potongan telah di-{$eventName}");
    }
}