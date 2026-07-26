<?php

namespace App\Filament\Widgets;

use App\Models\Penggajian;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class GrafikPengeluaran extends ChartWidget
{
    protected static ?string $heading = 'Grafik Pengeluaran Gaji (Per Bulan)';
    
    // Urutan widget (biar muncul di bawah kartu statistik)
    protected static ?int $sort = 2;
    
    // Agar grafik memenuhi lebar layar
    protected int | string | array $columnSpan = 'full';

    // 👇 INI FUNGSI YANG DITAMBAHKAN UNTUK KEAMANAN 👇
    public static function canView(): bool
    {
        // Hanya Super Admin dan Owner yang diizinkan melihat grafik ini
        return auth()->user()->hasRole(['super_admin', 'Owner']);
    }
    // 👆 ========================================== 👆

    protected function getData(): array
    {
        // Ambil data penggajian, kelompokkan berdasarkan Bulan
        $data = Penggajian::select('gaji_bersih', 'tanggal_gajian')
            ->get()
            ->groupBy(function ($date) {
                // Grouping format "Bulan Tahun" (Contoh: Jan 2026)
                return Carbon::parse($date->tanggal_gajian)->format('M Y');
            });

        // Siapkan data untuk grafik
        $quantities = [];
        $labels = [];

        foreach ($data as $bulan => $values) {
            $quantities[] = $values->sum('gaji_bersih'); // Total gaji bulan itu
            $labels[] = $bulan; // Label bulan
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Gaji Dibayarkan (Rp)',
                    'data' => $quantities,
                    'backgroundColor' => '#36A2EB', // Warna Biru
                    'borderColor' => '#9BD0F5',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line'; // Bisa diganti 'bar' kalau mau diagram batang
    }
}