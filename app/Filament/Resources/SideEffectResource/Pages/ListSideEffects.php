<?php

namespace App\Filament\Resources\SideEffectResource\Pages;

use App\Filament\Resources\SideEffectResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSideEffects extends ListRecords
{
    protected static string $resource = SideEffectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
