<?php

namespace App\Filament\Widgets;

use App\Models\Karyawan;
use App\Models\Jabatan;
use App\Models\Penggajian;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    // Atur waktu refresh data (opsional, misal tiap 15 detik)
    protected static ?string $pollingInterval = '15s';

    // 👇 INI FUNGSI YANG DITAMBAHKAN UNTUK KEAMANAN 👇
    public static function canView(): bool
    {
        // Hanya Super Admin dan Owner yang diizinkan melihat widget ini
        return auth()->user()->hasRole(['super_admin', 'Owner']);
    }
    // 👆 ========================================== 👆

    protected function getStats(): array
    {
        return [
            // KARTU 1: Total Karyawan
            Stat::make('Total Karyawan', Karyawan::count())
                ->description('Jumlah pegawai aktif')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'), // Warna Hijau

            // KARTU 2: Total Jabatan
            Stat::make('Total Jabatan', Jabatan::count())
                ->description('Posisi tersedia')
                ->descriptionIcon('heroicon-m-briefcase')
                ->color('primary'), // Warna Kuning/Emas

            // KARTU 3: Total Uang Gaji Keluar
            Stat::make('Total Pengeluaran Gaji', 'Rp ' . number_format(Penggajian::sum('gaji_bersih'), 0, ',', '.'))
                ->description('Total gaji yang sudah dibayar')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('danger'), // Warna Merah (karena uang keluar)
        ];
    }
}