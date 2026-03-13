<?php

namespace App\Filament\Resources\EffectResource\Pages;

use App\Filament\Resources\EffectResource;
use Filament\Actions;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewEffect extends ViewRecord
{
    protected static string $resource = EffectResource::class;

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
                Section::make('Effect Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('slug')
                                    ->label('Effect Name')
                                    ->size('lg')
                                    ->weight('bold')
                                    ->color('primary')
                                    ->copyable()
                                    ->copyMessage('Effect name copied')
                                    ->copyMessageDuration(1500),

                                TextEntry::make('medicines_count')
                                    ->label('Associated Medicines')
                                    ->getStateUsing(fn ($record) => $record->medicines()->count())
                                    ->badge()
                                    ->color('success')
                                    ->size('lg'),
                            ]),

                        TextEntry::make('text')
                            ->label('Description')
                            ->placeholder('No description provided')
                            ->prose()
                            ->columnSpanFull()
                            ->visible(fn ($record) => filled($record->text)),
                    ]),

                Section::make('Usage Statistics')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('prescription_medicines_count')
                                    ->label('Prescription Medicines')
                                    ->getStateUsing(fn ($record) => $record->medicines()->where('doctor_prescription', true)->count())
                                    ->badge()
                                    ->color('danger'),

                                TextEntry::make('otc_medicines_count')
                                    ->label('Over-the-Counter')
                                    ->getStateUsing(fn ($record) => $record->medicines()->where('doctor_prescription', false)->count())
                                    ->badge()
                                    ->color('success'),

                                TextEntry::make('brands_count')
                                    ->label('Different Brands')
                                    ->getStateUsing(fn ($record) => $record->medicines()->whereNotNull('brand_id')->distinct('brand_id')->count())
                                    ->badge()
                                    ->color('info'),
                            ]),
                    ])
                    ->visible(fn ($record) => $record->medicines()->count() > 0),

                // Section::make('Most Common Side Effects')
                //     ->schema([
                //         TextEntry::make('common_side_effects')
                //             ->label('')
                //             ->getStateUsing(function ($record) {
                //                 $sideEffects = $record->medicines()
                //                     ->with('sideEffects')
                //                     ->get()
                //                     ->pluck('sideEffects')
                //                     ->flatten()
                //                     ->groupBy('slug')
                //                     ->map(fn ($group) => $group->count())
                //                     ->sortDesc()
                //                     ->take(10);

                //                 return $sideEffects->keys()->toArray();
                //             })
                //             ->badge()
                //             ->color('warning')
                //             ->placeholder('No common side effects found'),
                //     ])
                //     ->visible(fn ($record) => $record->medicines()->count() > 0)
                //     ->collapsed(),

                Section::make('Record Information')
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
                    ])
                    ->collapsed(),
            ]);
    }
}
