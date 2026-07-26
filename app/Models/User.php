<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
// 👇 1. Tambahan untuk memanggil fitur Log Aktivitas 👇
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    // 👇 2. Tambahkan tulisan LogsActivity di baris ini 👇
    use HasFactory, Notifiable, HasRoles, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // 👇 3. Tambahkan fungsi konfigurasi Log di sini 👇
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll() // Merekam semua perubahan pada kolom
            ->logOnlyDirty() // Hanya merekam data yang benar-benar diubah
            ->dontSubmitEmptyLogs() // Mencegah pembuatan log kosong
            ->dontLogIfAttributesChangedOnly(['remember_token']) // Abaikan log jika sistem hanya memperbarui token login
            ->setDescriptionForEvent(fn(string $eventName) => "Data Akun User telah di-{$eventName}");
    }
}