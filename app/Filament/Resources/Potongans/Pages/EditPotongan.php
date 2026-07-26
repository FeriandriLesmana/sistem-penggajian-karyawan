<?php

namespace App\Filament\Resources\Potongans\Pages;

use App\Filament\Resources\Potongans\PotonganResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPotongan extends EditRecord
{
    protected static string $resource = PotonganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
