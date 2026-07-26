<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class WelcomeKaryawanWidget extends Widget
{
    protected static string $view = 'filament.widgets.welcome-karyawan-widget';

    // Membuat tampilan widget melebar penuh (full width)
    protected int | string | array $columnSpan = 'full';

    // Widget ini HANYA KHUSUS untuk role Karyawan
    public static function canView(): bool
    {
        return auth()->user()->hasRole('Karyawan');
    }
}