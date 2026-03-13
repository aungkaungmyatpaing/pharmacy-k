<?php

namespace App\Filament\Resources\PediatricDosageResource\Pages;

use App\Filament\Resources\PediatricDosageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPediatricDosage extends EditRecord
{
    protected static string $resource = PediatricDosageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
