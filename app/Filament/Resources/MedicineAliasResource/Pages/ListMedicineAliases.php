<?php

namespace App\Filament\Resources\MedicineAliasResource\Pages;

use App\Filament\Resources\MedicineAliasResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMedicineAliases extends ListRecords
{
    protected static string $resource = MedicineAliasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
