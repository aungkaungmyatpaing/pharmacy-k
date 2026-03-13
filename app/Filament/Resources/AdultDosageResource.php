<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdultDosageResource\Pages;
use App\Models\AdultDosage;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AdultDosageResource extends Resource
{
    protected static ?string $model = AdultDosage::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Medicine Management';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Dosage Information')
                    ->description('Enter the dosage details for an adult patient')
                    ->schema([
                        Forms\Components\Select::make('medicine_id')
                            ->label('Medicine')
                            ->relationship('medicine', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->placeholder('Select the medicine'),

                        Forms\Components\TextInput::make('slug')
                            ->label('Dosage Label (Slug)')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g. adult-standard-dose')
                            ->helperText('A short identifier for this dosage entry'),

                        Forms\Components\Textarea::make('text')
                            ->label('Dosage Description')
                            ->rows(5)
                            ->placeholder('e.g. 500mg twice daily after meals')
                            ->helperText('Full description of the adult dosage instructions'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('medicine.name')
                    ->label('Medicine')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),

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
            ->filters([
                Tables\Filters\SelectFilter::make('medicine_id')
                    ->label('Medicine')
                    ->relationship('medicine', 'name')
                    ->searchable()
                    ->preload()
                    ->placeholder('All Medicines'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('medicine.name', 'asc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAdultDosages::route('/'),
            'create' => Pages\CreateAdultDosage::route('/create'),
            'view'   => Pages\ViewAdultDosage::route('/{record}'),
            'edit'   => Pages\EditAdultDosage::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['slug', 'text', 'medicine.name'];
    }
}
