<?php

namespace App\Filament\Resources\Absensis\Pages;

use App\Filament\Resources\Absensis\AbsensiResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAbsensi extends CreateRecord
{
    protected static string $resource = AbsensiResource::class;

    protected static ?string $title = 'Tambah Data Absensi';

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()
                ->label('Buat Data'),
            $this->getCreateAnotherFormAction()
                ->label('Buat & Tambah Baru'),
            $this->getCancelFormAction()
                ->label('Batal'),
        ];
    }
}