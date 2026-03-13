<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PediatricDosageResource\Pages;
use App\Models\PediatricDosage;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PediatricDosageResource extends Resource
{
    protected static ?string $model = PediatricDosage::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Pediatric Dosages';

    protected static ?string $navigationGroup = 'Medicine Management';

    protected static ?int $navigationSort = 4;

    protected static ?string $modelLabel = 'Pediatric Dosage';

    protected static ?string $pluralModelLabel = 'Pediatric Dosages';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('ကလေးဆေးဖြတ်တန်း အချက်အလက်')
                    ->description('ကလေး အသက်အပိုင်းအခြားနှင့် ကိုယ်အလေးချိန်အလိုက် ဆေးဖြတ်တန်း')
                    ->schema([
                        Forms\Components\Select::make('medicine_id')
                            ->label('ဆေး')
                            ->relationship('medicine', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->placeholder('ဆေးတစ်မျိုး ရွေးပါ'),

                        Forms\Components\TextInput::make('age_group')
                            ->label('အသက်အပိုင်းအခြား')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('e.g. 1-5 years, Neonate, 6-12 years')
                            ->helperText('ဆေးဖြတ်တန်းသက်ဆိုင်ရာ အသက်အပိုင်းအခြား'),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('weight_kg_min')
                                    ->label('အနည်းဆုံး ကိုယ်အလေးချိန် (kg)')
                                    ->numeric()
                                    ->minValue(0)
                                    ->step(0.1)
                                    ->placeholder('e.g. 10.0')
                                    ->suffix('kg'),

                                Forms\Components\TextInput::make('weight_kg_max')
                                    ->label('အများဆုံး ကိုယ်အလေးချိန် (kg)')
                                    ->numeric()
                                    ->minValue(0)
                                    ->step(0.1)
                                    ->placeholder('e.g. 20.0')
                                    ->suffix('kg'),
                            ]),

                        Forms\Components\TextInput::make('slug')
                            ->label('Label (Slug)')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g. pediatric-1-5yr-standard'),

                        Forms\Components\Textarea::make('text')
                            ->label('ဆေးဖြတ်တန်း ညွှန်ကြားချက်')
                            ->rows(5)
                            ->placeholder('e.g. 125mg every 6 hours, max 4 doses/day')
                            ->helperText('ကလေးဆေးဖြတ်တန်း အပြည့်အစုံ ညွှန်ကြားချက်')
                            ->columnSpanFull(),
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

                Tables\Columns\TextColumn::make('age_group')
                    ->label('အသက်အပိုင်းအခြား')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('weight_kg_min')
                    ->label('Min (kg)')
                    ->suffix(' kg')
                    ->numeric(decimalPlaces: 1)
                    ->color('gray')
                    ->size('sm')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('weight_kg_max')
                    ->label('Max (kg)')
                    ->suffix(' kg')
                    ->numeric(decimalPlaces: 1)
                    ->color('gray')
                    ->size('sm')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('slug')
                    ->label('Label')
                    ->searchable()
                    ->color('gray')
                    ->size('sm'),

                Tables\Columns\TextColumn::make('text')
                    ->label('ညွှန်ကြားချက်')
                    ->limit(70)
                    ->color('gray')
                    ->size('sm'),

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
            'index'  => Pages\ListPediatricDosages::route('/'),
            'create' => Pages\CreatePediatricDosage::route('/create'),
            'view'   => Pages\ViewPediatricDosage::route('/{record}'),
            'edit'   => Pages\EditPediatricDosage::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['age_group', 'slug', 'text', 'medicine.name'];
    }
}
