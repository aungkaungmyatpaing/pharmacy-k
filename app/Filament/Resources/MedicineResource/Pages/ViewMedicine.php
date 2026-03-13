<?php

namespace App\Filament\Resources\MedicineResource\Pages;

use App\Filament\Resources\MedicineResource;
use Dom\Text;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\Grid;

class ViewMedicine extends ViewRecord
{
    protected static string $resource = MedicineResource::class;

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
                Section::make('Medicine Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('name')
                                    ->label('Medicine Name')
                                    ->size('lg')
                                    ->weight('bold')
                                    ->color('primary'),

                                TextEntry::make('chemical_name')
                                    ->label('Generic/Chemical Name')
                                    ->color('gray'),
                            ]),

                        Grid::make(3)
                            ->schema([
                                TextEntry::make('brand.name')
                                    ->label('Brand')
                                    ->badge()
                                    ->color('info')
                                    ->placeholder('No Brand'),

                                TextEntry::make('brand.company')
                                    ->label('Company')
                                    ->placeholder('N/A'),

                                IconEntry::make('doctor_prescription')
                                    ->label('Prescription Required')
                                    ->boolean()
                                    ->trueIcon('heroicon-o-check-circle')
                                    ->falseIcon('heroicon-o-x-circle')
                                    ->trueColor('danger')
                                    ->falseColor('success'),
                            ]),
                    ]),

                Section::make('Mechanism of Action')
                    ->schema([
                        TextEntry::make('moa')
                            ->label('')
                            ->placeholder('No mechanism of action provided')
                            ->prose(),
                    ])
                    ->visible(fn($record) => filled($record->moa)),

                Section::make('Cautions & Warnings')
                    ->schema([
                        TextEntry::make('caution')
                            ->label('')
                            ->placeholder('No caution information provided')
                            ->prose(),
                    ])
                    ->visible(fn($record) => filled($record->caution)),

                Section::make('Therapeutic Effects')
                    ->schema([
                        TextEntry::make('effects.slug')
                            ->label('')
                            ->badge()
                            ->color('success')
                            ->placeholder('No effects specified'),
                    ])
                    ->visible(fn($record) => $record->effects->count() > 0),

                Section::make('Side Effects')
                    ->schema([
                        TextEntry::make('sideEffects.slug')
                            ->label('')
                            ->badge()
                            ->color('warning')
                            ->placeholder('No side effects specified'),
                    ])
                    ->visible(fn($record) => $record->sideEffects->count() > 0),

                Section::make('Drug Interactions')
                    ->schema([
                        TextEntry::make('drug_interactions')
                            ->label('')
                            ->placeholder('No drug interactions provided')
                            ->prose(),
                    ])
                    ->visible(fn($record) => filled($record->drug_interactions)),

                Section::make('Storage Condition')
                    ->schema([
                        TextEntry::make('storage_conditions')
                            ->label('')
                            ->placeholder('No storage condition provided')
                            ->prose(),
                    ])
                    ->visible(fn($record) => filled($record->storage_conditions)),

                Section::make('Overdose Emergency')
                    ->schema([
                        TextEntry::make('overdose_emergency')
                            ->label('')
                            ->placeholder('No overdose emergency provided')
                            ->prose(),
                    ])
                    ->visible(fn($record) => filled($record->overdose_emergency)),

                Section::make('Contraindications')
                    ->schema([
                        TextEntry::make('contraindications')
                            ->label('')
                            ->placeholder('No contraindications provided')
                            ->prose(),
                    ])
                    ->visible(fn($record) => filled($record->contraindications)),

                Section::make('Dosage Form & Strength')
                    ->schema([
                        TextEntry::make('dosage_form')
                            ->label('Dosage Form')
                            ->formatStateUsing(fn($state) => [
                                'Tablet' => 'Tablet',
                                'Capsule' => 'Capsule',
                                'Syrup' => 'Syrup',
                                'Suspension' => 'Suspension',
                                'Injection' => 'Injection',
                                'IV Infusion' => 'IV Infusion',
                                'Cream' => 'Cream',
                                'Ointment' => 'Ointment',
                                'Gel' => 'Gel',
                                'Eye Drop' => 'Eye Drop',
                                'Ear Drop' => 'Ear Drop',
                                'Nasal Spray' => 'Nasal Spray',
                                'Inhaler' => 'Inhaler',
                                'Patch' => 'Patch',
                                'Suppository' => 'Suppository',
                                'Powder' => 'Powder',
                                'Other' => 'Other',
                            ][$state] ?? $state)
                            ->badge()
                            ->color('primary'),
                        TextEntry::make('strength')
                            ->label('Strength / Concentration')
                            ->badge()
                            ->color('info'),
                        TextEntry::make('route_of_administration')
                            ->label('Route of Administration')
                            ->formatStateUsing(fn($state) => [
                                'Oral' => 'Oral (By mouth)',
                                'IV' => 'Intravenous (IV)',
                                'IM' => 'Intramuscular (IM)',
                                'SC' => 'Subcutaneous (SC)',
                                'Topical' => 'Topical (Skin)',
                                'Inhaled' => 'Inhaled',
                                'Sublingual' => 'Sublingual (Under tongue)',
                                'Rectal' => 'Rectal',
                                'Ophthalmic' => 'Ophthalmic (Eye)',
                                'Otic' => 'Otic (Ear)',
                                'Nasal' => 'Nasal',
                                'Transdermal' => 'Transdermal (Patch)',
                                'Other' => 'Other',
                            ][$state] ?? $state)
                            ->badge()
                            ->color('danger')
                    ]),

                Section::make('Pregnancy & Lactation')
                    ->schema([
                        TextEntry::make('pregnancy_category')
                            ->label('FDA Pregnancy Category')
                            ->formatStateUsing(fn($state) => [
                                'A' => 'Category A — Safe (Adequate studies show no risk)',
                                'B' => 'Category B — Probably Safe (Animal studies OK, no human studies)',
                                'C' => 'Category C — Use with Caution (Risk not ruled out)',
                                'D' => 'Category D — Unsafe (Evidence of risk, benefits may outweigh)',
                                'X' => 'Category X — Contraindicated (Risk clearly outweighs benefit)',
                                'N' => 'Category N — Not Classified',
                            ][$state] ?? $state),

                        TextEntry::make('lactation_safety')
                            ->label('')
                            ->placeholder('No lactation safety information provided')
                            ->prose(),
                    ])
                    ->visible(fn($record) => filled($record->lactation_safety)),

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
