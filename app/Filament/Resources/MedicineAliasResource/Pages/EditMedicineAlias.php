<?php

namespace App\Filament\Resources\MedicineAliasResource\Pages;

use App\Filament\Resources\MedicineAliasResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMedicineAlias extends EditRecord
{
    protected static string $resource = MedicineAliasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
