<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EffectResource\Pages;
use App\Filament\Resources\EffectResource\RelationManagers;
use App\Filament\Resources\EffectResource\RelationManagers\MedicinesRelationManager;
use App\Models\Effect;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class EffectResource extends Resource
{
    protected static ?string $model = Effect::class;

    protected static ?string $navigationIcon = 'heroicon-o-heart';

    protected static ?string $navigationGroup = 'Medicine Management';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Therapeutic Effect';

    protected static ?string $pluralModelLabel = 'Therapeutic Effects';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Effect Information')
                    ->description('Define a therapeutic effect that medicines can provide')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('မြန်မာဘာသာ နာမည် (အတို)')
                                    ->required()
                                    ->maxLength(100)
                                    ->placeholder('ဥပမာ: အဖျားကျ, နာကျင်မှုသက်သာ, ဘက်တီးရီးယားသတ်')
                                    ->helperText('Admin panel နှင့် ဆေးဖော်မြူလာ ရွေးချယ်ချိန်တွင် ပြသမည့်နာမည်'),

                                Forms\Components\TextInput::make('slug')
                                    ->label('Slug (English Identifier)')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255)
                                    ->placeholder('pain-relief, anti-inflammatory')
                                    ->helperText('Lowercase + hyphens သာ။ API/URL တွင် သုံးသည်။')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (string $context, $state, Forms\Set $set) {
                                        if ($context === 'create') {
                                            $set('slug', Str::slug($state));
                                        }
                                    })
                                    ->rules(['regex:/^[a-z0-9-]+$/']),
                            ]),

                        Forms\Components\Textarea::make('text')
                            ->label('အသေးစိတ် ဖော်ပြချက်')
                            ->rows(4)
                            ->maxLength(1000)
                            ->placeholder('ဤ effect သည် မည်သည့်ရောဂါကို ကုသနိုင်သည်ကို ရှင်းပြပါ...')
                            ->helperText('Optional: ဆေးသောက်သူ ဖတ်ရှုနိုင်သော အသေးစိတ်ဖော်ပြချက်')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Effect နာမည် (မြန်မာ)')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->sortable()
                    ->color('gray')
                    ->size('sm')
                    ->copyable()
                    ->copyMessage('Slug copied')
                    ->copyMessageDuration(1500),

                Tables\Columns\TextColumn::make('text')
                    ->label('ဖော်ပြချက်')
                    ->searchable()
                    ->limit(60)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 60) {
                            return null;
                        }
                        return $state;
                    })
                    ->placeholder('မရှိသေး')
                    ->color('gray'),

                Tables\Columns\TextColumn::make('medicines_count')
                    ->label('Associated Medicines')
                    ->counts('medicines')
                    ->badge()
                    ->color('success')
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
                Filter::make('has_description')
                    ->label('Has Description')
                    ->query(fn(Builder $query): Builder => $query->whereNotNull('text')->where('text', '!=', '')),

                Filter::make('no_description')
                    ->label('No Description')
                    ->query(fn(Builder $query): Builder => $query->whereNull('text')->orWhere('text', '')),

                Filter::make('has_medicines')
                    ->label('Used in Medicines')
                    ->query(fn(Builder $query): Builder => $query->has('medicines')),

                Filter::make('unused')
                    ->label('Unused Effects')
                    ->query(fn(Builder $query): Builder => $query->doesntHave('medicines')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalDescription('Are you sure you want to delete this effect? It will be removed from all associated medicines.')
                    ->successNotificationTitle('Effect deleted'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->modalDescription('Are you sure you want to delete these effects? They will be removed from all associated medicines.'),
                ]),
            ])
            ->defaultSort('slug', 'asc')
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
            'index' => Pages\ListEffects::route('/'),
            'create' => Pages\CreateEffect::route('/create'),
            'view' => Pages\ViewEffect::route('/{record}'),
            'edit' => Pages\EditEffect::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'slug', 'text'];
    }

    public static function getGlobalSearchResultTitle($record): string
    {
        return $record->name ?? $record->slug;
    }

    public static function getGlobalSearchResultDetails($record): array
    {
        $details = [];

        if ($record->text) {
            $details['Description'] = Str::limit($record->text, 50);
        }

        $details['Medicines'] = $record->medicines()->count() . ' associated';

        return $details;
    }
}
