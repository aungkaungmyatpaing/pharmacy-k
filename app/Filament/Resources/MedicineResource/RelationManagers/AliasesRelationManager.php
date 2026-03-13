<?php

namespace App\Filament\Resources\MedicineResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class AliasesRelationManager extends RelationManager
{
    protected static string $relationship = 'aliases';

    protected static ?string $title = 'Alternative Names & Aliases';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('alias')
                    ->label('Alternative Name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('e.g. Panadol, ပက်နာဒေါ်, Tylenol'),

                Forms\Components\Select::make('language')
                    ->label('Language / Type')
                    ->options([
                        'en'    => '🇬🇧 English',
                        'mm'    => '🇲🇲 Myanmar (Burmese)',
                        'trade' => '🏷️ Trade Name',
                        'other' => '🌐 Other',
                    ])
                    ->default('en')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('alias')
            ->columns([
                Tables\Columns\TextColumn::make('alias')
                    ->label('Alternative Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\BadgeColumn::make('language')
                    ->label('Language / Type')
                    ->colors([
                        'info'    => 'en',
                        'warning' => 'mm',
                        'success' => 'trade',
                        'gray'    => 'other',
                    ])
                    ->formatStateUsing(fn($state) => match ($state) {
                        'en'    => 'English',
                        'mm'    => 'Myanmar',
                        'trade' => 'Trade Name',
                        default => 'Other',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Added')
                    ->dateTime()
                    ->sortable()
                    ->size('sm')
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Add Alias'),
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
