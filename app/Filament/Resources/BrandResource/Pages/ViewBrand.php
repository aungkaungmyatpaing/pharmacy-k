<?php

namespace App\Filament\Resources\BrandResource\Pages;

use App\Filament\Resources\BrandResource;
use Filament\Actions;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewBrand extends ViewRecord
{
    protected static string $resource = BrandResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Brand Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('name')
                                    ->label('Brand Name')
                                    ->weight('bold')
                                    ->size('lg')
                                    ->color('primary')
                                    ->copyable()
                                    ->copyMessage('Brand name copied')
                                    ->copyMessageDuration(1500),

                                TextEntry::make('company')
                                    ->label('Company / Manufacturer')
                                    ->placeholder('No company specified')
                                    ->color('gray'),
                            ]),
                    ]),

                Section::make('Associated Medicines')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('medicines_count')
                                    ->label('Total Medicines')
                                    ->getStateUsing(fn ($record) => $record->medicines()->count())
                                    ->badge()
                                    ->color('info'),

                                TextEntry::make('prescription_medicines_count')
                                    ->label('Prescription Medicines')
                                    ->getStateUsing(fn ($record) => $record->medicines()->where('doctor_prescription', true)->count())
                                    ->badge()
                                    ->color('danger'),

                                TextEntry::make('otc_medicines_count')
                                    ->label('Over-the-Counter Medicines')
                                    ->getStateUsing(fn ($record) => $record->medicines()->where('doctor_prescription', false)->count())
                                    ->badge()
                                    ->color('success'),
                            ]),
                    ])
                    ->visible(fn ($record) => $record->medicines()->exists()),

                Section::make('Record Information')
                    ->collapsed()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('created_at')
                                    ->label('Created At')
                                    ->dateTime(),

                                TextEntry::make('updated_at')
                                    ->label('Updated At')
                                    ->dateTime(),
                            ]),
                    ]),
            ]);
    }
}
