<?php

namespace App\Filament\Resources\Penggajians\Pages;

use App\Filament\Resources\Penggajians\PenggajianResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePenggajian extends CreateRecord
{
    protected static string $resource = PenggajianResource::class;

    // 👇 1. MENGUBAH JUDUL HALAMAN 👇
    protected static ?string $title = 'Tambah Data Penggajian';

    // 👇 2. MENGUBAH TEKS TOMBOL BAWAH 👇
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