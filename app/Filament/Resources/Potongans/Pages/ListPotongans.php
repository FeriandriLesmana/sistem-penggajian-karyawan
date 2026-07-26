<?php

namespace App\Filament\Resources\Potongans\Pages;

use App\Filament\Resources\Potongans\PotonganResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPotongans extends ListRecords
{
    protected static string $resource = PotonganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
