<?php

namespace App\Filament\Resources\Penggajians\Pages;

use App\Filament\Resources\Penggajians\PenggajianResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPenggajian extends EditRecord
{
    protected static string $resource = PenggajianResource::class;

    // 👇 1. Mengubah Judul Halaman di atas 👇
    protected static ?string $title = 'Ubah Data Penggajian';

    // 👇 2. Mengubah tombol di pojok kanan atas (View & Delete) 👇
    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()
                ->label('Lihat'),
            DeleteAction::make()
                ->label('Hapus'),
        ];
    }

    // 👇 3. Mengubah tombol di bawah form (Save & Cancel) 👇
    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction()
                ->label('Simpan Perubahan'),
            $this->getCancelFormAction()
                ->label('Batal'),
        ];
    }
}