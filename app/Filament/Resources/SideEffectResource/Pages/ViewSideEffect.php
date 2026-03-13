<?php

namespace App\Filament\Resources\SideEffectResource\Pages;

use App\Filament\Resources\SideEffectResource;
use Filament\Actions;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewSideEffect extends ViewRecord
{
    protected static string $resource = SideEffectResource::class;

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
                Section::make('Side Effect Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('slug')
                                    ->label('Side Effect Name')
                                    ->size('lg')
                                    ->weight('bold')
                                    ->color('warning')
                                    ->copyable()
                                    ->copyMessage('Side effect name copied')
                                    ->copyMessageDuration(1500),

                                TextEntry::make('medicines_count')
                                    ->label('Associated Medicines')
                                    ->getStateUsing(fn ($record) => $record->medicines()->count())
                                    ->badge()
                                    ->color('warning')
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

                // Section::make('Most Common Effects Alongside This Side Effect')
                //     ->schema([
                //         TextEntry::make('common_effects')
                //             ->label('')
                //             ->getStateUsing(function ($record) {
                //                 $effects = $record->medicines()
                //                     ->with('effects')
                //                     ->get()
                //                     ->pluck('effects')
                //                     ->flatten()
                //                     ->groupBy('slug')
                //                     ->map(fn ($group) => $group->count())
                //                     ->sortDesc()
                //                     ->take(10);

                //                 return $effects->keys()->toArray();
                //             })
                //             ->badge()
                //             ->color('success')
                //             ->placeholder('No common effects found'),
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
