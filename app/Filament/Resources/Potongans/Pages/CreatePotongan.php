<?php

namespace App\Filament\Resources\Potongans\Pages;

use App\Filament\Resources\Potongans\PotonganResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePotongan extends CreateRecord
{
    protected static string $resource = PotonganResource::class;

    protected static ?string $title = 'Tambah Data Potongan';

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()->label('Buat Data'),
            $this->getCreateAnotherFormAction()->label('Buat & Tambah Baru'),
            $this->getCancelFormAction()->label('Batal'),
        ];
    }
}