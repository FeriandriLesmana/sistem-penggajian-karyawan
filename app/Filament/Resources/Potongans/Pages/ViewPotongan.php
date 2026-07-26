<?php

namespace App\Filament\Resources\Potongans\Pages;

use App\Filament\Resources\Potongans\PotonganResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPotongan extends ViewRecord
{
    protected static string $resource = PotonganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
