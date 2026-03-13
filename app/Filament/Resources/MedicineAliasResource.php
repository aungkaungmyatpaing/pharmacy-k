<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MedicineAliasResource\Pages;
use App\Models\MedicineAlias;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MedicineAliasResource extends Resource
{
    protected static ?string $model = MedicineAlias::class;

    protected static ?string $navigationIcon = 'heroicon-o-language';

    protected static ?string $navigationLabel = 'Alternative Names';

    protected static ?string $navigationGroup = 'Medicine Management';

    protected static ?int $navigationSort = 5;

    protected static ?string $modelLabel = 'Alternative Name';

    protected static ?string $pluralModelLabel = 'Alternative Names';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('ဆေးအမည် အခြားဖော်ပြချက်')
                    ->description('ဆေးတစ်မျိုး၏ တခြားဘာသာဖြင့် သော်လည်းကောင်း၊ လူသိများသော Trade name တို့ကို ထည့်ပါ')
                    ->schema([
                        Forms\Components\Select::make('medicine_id')
                            ->label('ဆေး')
                            ->relationship('medicine', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->placeholder('ဆေးတစ်မျိုး ရွေးပါ'),

                        Forms\Components\TextInput::make('alias')
                            ->label('အမည် (Alias)')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g. Panadol, ပက်နာဒေါ်, Tylenol')
                            ->helperText('Trade name, မြန်မာနာမည် သို့မဟုတ် တခြားဘာသာ၏ ဆေးနာမည်'),

                        Forms\Components\Select::make('language')
                            ->label('ဘာသာ / အမျိုးအစား')
                            ->options([
                                'en'    => '🇬🇧 English',
                                'mm'    => '🇲🇲 မြန်မာ (Myanmar)',
                                'trade' => '🏷️ Trade Name',
                                'other' => '🌐 အခြား (Other)',
                            ])
                            ->default('en')
                            ->required()
                            ->native(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('medicine.name')
                    ->label('ဆေးအမည်')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('alias')
                    ->label('လူသိများသောအမည်')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\BadgeColumn::make('language')
                    ->label('ဘာသာ / အမျိုးအစား')
                    ->colors([
                        'info'    => 'en',
                        'warning' => 'mm',
                        'success' => 'trade',
                        'gray'    => 'other',
                    ])
                    ->formatStateUsing(fn($state) => match ($state) {
                        'en'    => '🇬🇧 English',
                        'mm'    => '🇲🇲 မြန်မာ',
                        'trade' => '🏷️ Trade Name',
                        default => '🌐 အခြား',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('ထည့်သွင်းသည့်ရက်')
                    ->dateTime()
                    ->sortable()
                    ->size('sm')
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('medicine_id')
                    ->label('ဆေး')
                    ->relationship('medicine', 'name')
                    ->searchable()
                    ->preload()
                    ->placeholder('ဆေးအားလုံး'),

                SelectFilter::make('language')
                    ->label('ဘာသာ / အမျိုးအစား')
                    ->options([
                        'en'    => '🇬🇧 English',
                        'mm'    => '🇲🇲 မြန်မာ',
                        'trade' => '🏷️ Trade Name',
                        'other' => '🌐 အခြား',
                    ])
                    ->placeholder('အားလုံး'),
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
            'index'  => Pages\ListMedicineAliases::route('/'),
            'create' => Pages\CreateMedicineAlias::route('/create'),
            'view'   => Pages\ViewMedicineAlias::route('/{record}'),
            'edit'   => Pages\EditMedicineAlias::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['alias', 'medicine.name'];
    }
}
