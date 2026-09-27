<?php

namespace App\Filament\Resources\Potongans\Pages;

use App\Filament\Resources\Potongans\PotonganResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPotongan extends EditRecord
{
    protected static string $resource = PotonganResource::class;

    protected static ?string $title = 'Ubah Data Potongan';

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()->label('Lihat'),
            DeleteAction::make()->label('Hapus'),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction()->label('Simpan Perubahan'),
            $this->getCancelFormAction()->label('Batal'),
        ];
    }
}