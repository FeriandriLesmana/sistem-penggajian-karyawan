<?php

namespace App\Filament\Resources\Jabatans\Pages;

use App\Filament\Resources\Jabatans\JabatanResource;
use Filament\Resources\Pages\CreateRecord;

class CreateJabatan extends CreateRecord
{
    protected static string $resource = JabatanResource::class;

    protected static ?string $title = 'Tambah Data Jabatan';

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