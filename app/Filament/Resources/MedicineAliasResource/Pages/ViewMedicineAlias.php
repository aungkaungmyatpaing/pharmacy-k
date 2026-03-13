<?php

namespace App\Filament\Resources\MedicineAliasResource\Pages;

use App\Filament\Resources\MedicineAliasResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMedicineAlias extends ViewRecord
{
    protected static string $resource = MedicineAliasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
