<?php

namespace App\Filament\Resources\AdultDosageResource\Pages;

use App\Filament\Resources\AdultDosageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAdultDosages extends ListRecords
{
    protected static string $resource = AdultDosageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
