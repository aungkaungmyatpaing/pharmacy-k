<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BrandResource\Pages;
use App\Filament\Resources\BrandResource\RelationManagers;
use App\Filament\Resources\BrandResource\RelationManagers\MedicinesRelationManager;
use App\Models\Brand;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BrandResource extends Resource
{
    protected static ?string $model = Brand::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationGroup = 'Medicine Management';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Brand Information')
                    ->description('Enter the brand and manufacturer details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Brand Name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Enter brand name (e.g., Tylenol, Advil)')
                            ->helperText('The commercial brand name of the medicine'),

                        Forms\Components\TextInput::make('company')
                            ->label('Company/Manufacturer')
                            ->maxLength(255)
                            ->placeholder('Enter company name (e.g., Johnson & Johnson, Pfizer)')
                            ->helperText('Optional: The pharmaceutical company that manufactures this brand'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Brand Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('company')
                    ->label('Company')
                    ->searchable()
                    ->sortable()
                    ->placeholder('No company specified')
                    ->color('gray'),

                Tables\Columns\TextColumn::make('medicines_count')
                    ->label('Medicines')
                    ->counts('medicines')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                Tables\Columns\TextColumn::make('prescription_medicines_count')
                    ->label('Rx Medicines')
                    ->getStateUsing(fn ($record) => $record->medicines()->where('doctor_prescription', true)->count())
                    ->badge()
                    ->color('danger')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->size('sm')
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->sortable()
                    ->size('sm')
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('has_company')
                    ->label('Has Company Info')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('company')->where('company', '!=', '')),

                Filter::make('no_company')
                    ->label('No Company Info')
                    ->query(fn (Builder $query): Builder => $query->whereNull('company')->orWhere('company', '')),

                Filter::make('has_medicines')
                    ->label('With Medicines')
                    ->query(fn (Builder $query): Builder => $query->has('medicines')),

                Filter::make('no_medicines')
                    ->label('No Medicines')
                    ->query(fn (Builder $query): Builder => $query->doesntHave('medicines')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalDescription('Are you sure you want to delete this brand? Associated medicines will have their brand set to null.')
                    ->successNotificationTitle('Brand deleted'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->modalDescription('Are you sure you want to delete these brands? Associated medicines will have their brand set to null.'),
                ]),
            ])
            ->defaultSort('name', 'asc')
            ->striped();
    }


    public static function getRelations(): array
    {
        return [
            MedicinesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBrands::route('/'),
            'create' => Pages\CreateBrand::route('/create'),
            'view' => Pages\ViewBrand::route('/{record}'),
            'edit' => Pages\EditBrand::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'company'];
    }

    public static function getGlobalSearchResultTitle($record): string
    {
        return $record->name;
    }

    public static function getGlobalSearchResultDetails($record): array
    {
        $details = [];

        if ($record->company) {
            $details['Company'] = $record->company;
        }

        $details['Medicines'] = $record->medicines()->count() . ' medicines';

        return $details;
    }
}
