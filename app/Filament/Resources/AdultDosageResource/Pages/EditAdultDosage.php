<?php

namespace App\Filament\Resources\AdultDosageResource\Pages;

use App\Filament\Resources\AdultDosageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdultDosage extends EditRecord
{
    protected static string $resource = AdultDosageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
