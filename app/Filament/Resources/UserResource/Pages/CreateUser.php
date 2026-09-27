<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected static ?string $title = 'Tambah Akun Pengguna';

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()->label('Buat Akun'),
            $this->getCreateAnotherFormAction()->label('Buat & Tambah Baru'),
            $this->getCancelFormAction()->label('Batal'),
        ];
    }
}