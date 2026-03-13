<?php

namespace App\Filament\Resources\AdultDosageResource\Pages;

use App\Filament\Resources\AdultDosageResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAdultDosage extends ViewRecord
{
    protected static string $resource = AdultDosageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
