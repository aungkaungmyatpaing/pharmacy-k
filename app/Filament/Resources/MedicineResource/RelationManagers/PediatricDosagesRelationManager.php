<?php

namespace App\Filament\Resources\MedicineResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class PediatricDosagesRelationManager extends RelationManager
{
    protected static string $relationship = 'pediatricDosages';

    protected static ?string $title = 'Pediatric Dosages';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('age_group')
                    ->label('Age Group')
                    ->required()
                    ->maxLength(100)
                    ->placeholder('e.g. 1-5 years, Neonate, 6-12 years')
                    ->helperText('Target age range for this dosage'),

                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('weight_kg_min')
                            ->label('Min Weight (kg)')
                            ->numeric()
                            ->minValue(0)
                            ->step(0.1)
                            ->placeholder('e.g. 10.0'),

                        Forms\Components\TextInput::make('weight_kg_max')
                            ->label('Max Weight (kg)')
                            ->numeric()
                            ->minValue(0)
                            ->step(0.1)
                            ->placeholder('e.g. 20.0'),
                    ]),

                Forms\Components\TextInput::make('slug')
                    ->label('Dosage Label')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('e.g. pediatric-1-5yr-standard'),

                Forms\Components\Textarea::make('text')
                    ->label('Dosage Instructions')
                    ->rows(4)
                    ->placeholder('e.g. 125mg every 6 hours, max 4 doses/day'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('age_group')
            ->columns([
                Tables\Columns\TextColumn::make('age_group')
                    ->label('Age Group')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('weight_kg_min')
                    ->label('Min kg')
                    ->suffix(' kg')
                    ->color('gray')
                    ->size('sm')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('weight_kg_max')
                    ->label('Max kg')
                    ->suffix(' kg')
                    ->color('gray')
                    ->size('sm')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('slug')
                    ->label('Label')
                    ->color('gray')
                    ->size('sm'),

                Tables\Columns\TextColumn::make('text')
                    ->label('Instructions')
                    ->limit(70)
                    ->color('gray')
                    ->size('sm'),
            ])
            ->filters([])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Add Pediatric Dosage'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
