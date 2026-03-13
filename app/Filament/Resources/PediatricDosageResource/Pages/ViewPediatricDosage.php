<?php

namespace App\Filament\Resources\PediatricDosageResource\Pages;

use App\Filament\Resources\PediatricDosageResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPediatricDosage extends ViewRecord
{
    protected static string $resource = PediatricDosageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
