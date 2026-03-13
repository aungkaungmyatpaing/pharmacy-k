<?php

namespace App\Filament\Resources\MedicineResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class AdultDosagesRelationManager extends RelationManager
{
    protected static string $relationship = 'adultDosages';

    protected static ?string $title = 'Adult Dosages';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('slug')
                    ->label('Dosage Label (Slug)')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('e.g. adult-standard-dose'),

                Forms\Components\Textarea::make('text')
                    ->label('Dosage Description')
                    ->rows(4)
                    ->placeholder('e.g. 500mg twice daily after meals'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('slug')
            ->columns([
                Tables\Columns\TextColumn::make('slug')
                    ->label('Dosage Label')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('text')
                    ->label('Description')
                    ->limit(80)
                    ->color('gray')
                    ->size('sm'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->size('sm')
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Add Dosage'),
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
