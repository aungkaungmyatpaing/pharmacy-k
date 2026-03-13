<?php

namespace App\Filament\Resources\MedicineCategoryResource\Pages;

use App\Filament\Resources\MedicineCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMedicineCategory extends ViewRecord
{
    protected static string $resource = MedicineCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
