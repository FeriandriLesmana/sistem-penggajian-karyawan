<?php

namespace App\Filament\Resources\Karyawans\Pages;

use App\Filament\Resources\Karyawans\KaryawanResource;
use Filament\Resources\Pages\CreateRecord;

class CreateKaryawan extends CreateRecord
{
    protected static string $resource = KaryawanResource::class;

    protected static ?string $title = 'Tambah Data Karyawan';

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