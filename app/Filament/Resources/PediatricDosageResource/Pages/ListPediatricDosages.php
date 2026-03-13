<?php

namespace App\Filament\Resources\PediatricDosageResource\Pages;

use App\Filament\Resources\PediatricDosageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPediatricDosages extends ListRecords
{
    protected static string $resource = PediatricDosageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
