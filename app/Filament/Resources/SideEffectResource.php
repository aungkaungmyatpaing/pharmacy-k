<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SideEffectResource\Pages;
use App\Filament\Resources\SideEffectResource\RelationManagers;
use App\Filament\Resources\SideEffectResource\RelationManagers\MedicinesRelationManager;
use App\Models\SideEffect;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Str;

class SideEffectResource extends Resource
{
    protected static ?string $model = SideEffect::class;

    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';

    protected static ?string $navigationGroup = 'Medicine Management';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Side Effect';

    protected static ?string $pluralModelLabel = 'Side Effects';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Side Effect Information')
                    ->description('Define a side effect that medicines can cause')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('မြန်မာဘာသာ နာမည် (အတို)')
                                    ->required()
                                    ->maxLength(100)
                                    ->placeholder('ဥပမာ: ပျို့ချင်ခြင်း, ဝမ်းလျှော, ခေါင်းကိုက်')
                                    ->helperText('Admin panel နှင့် ဆေးဖော်မြူလာ ရွေးချယ်ချိန်တွင် ပြသမည့်နာမည်'),

                                Forms\Components\TextInput::make('slug')
                                    ->label('Slug (English Identifier)')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255)
                                    ->placeholder('nausea, diarrhea, headache')
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
                            ->placeholder('ဤ side effect ၏ ပြင်းထန်မှုနှင့် သတိပြုရမည့်အချက်များ...')
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
                    ->label('Side Effect နာမည် (မြန်မာ)')
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
                    ->color('warning')
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
                    ->label('Unused Side Effects')
                    ->query(fn(Builder $query): Builder => $query->doesntHave('medicines')),

                Filter::make('common')
                    ->label('Common Side Effects')
                    ->query(fn(Builder $query): Builder => $query->has('medicines', '>=', 5)),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalDescription('Are you sure you want to delete this side effect? It will be removed from all associated medicines.')
                    ->successNotificationTitle('Side effect deleted'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->modalDescription('Are you sure you want to delete these side effects? They will be removed from all associated medicines.'),
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
            'index' => Pages\ListSideEffects::route('/'),
            'create' => Pages\CreateSideEffect::route('/create'),
            'view' => Pages\ViewSideEffect::route('/{record}'),
            'edit' => Pages\EditSideEffect::route('/{record}/edit'),
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
