<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    // 👇 1. Menambahkan Judul Halaman 👇
    protected static ?string $title = 'Data Pengguna';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                // 👇 2. Mengubah Tombol Pojok Kanan Atas 👇
                ->label('Tambah Pengguna'),
        ];
    }
}