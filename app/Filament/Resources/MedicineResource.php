<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MedicineResource\Pages;
use App\Filament\Resources\MedicineResource\RelationManagers\EffectsRelationManager;
use App\Filament\Resources\MedicineResource\RelationManagers\SideEffectsRelationManager;
use App\Filament\Resources\MedicineResource\RelationManagers\CategoriesRelationManager;
use App\Filament\Resources\MedicineResource\RelationManagers\AdultDosagesRelationManager;
use App\Filament\Resources\MedicineResource\RelationManagers\PediatricDosagesRelationManager;
use App\Filament\Resources\MedicineResource\RelationManagers\AliasesRelationManager;
use App\Models\Medicine;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class MedicineResource extends Resource
{
    protected static ?string $model = Medicine::class;

    protected static ?string $navigationIcon = 'heroicon-o-beaker';

    protected static ?string $navigationGroup = 'Medicine Management';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // ────────────────────────────────────────────
                // 1. BASIC INFORMATION
                // ────────────────────────────────────────────
                Section::make('Basic Information')
                    ->description('Enter the basic details of the medicine')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255)
                                    ->placeholder('Enter medicine name')
                                    ->helperText('Commercial / trade name of the medicine'),

                                Forms\Components\Select::make('brand_id')
                                    ->label('Brand')
                                    ->relationship('brand', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->required()->maxLength(255),
                                        Forms\Components\TextInput::make('company')
                                            ->maxLength(255),
                                    ])
                                    ->nullable()
                                    ->placeholder('Select or create a brand'),
                            ]),

                        Forms\Components\TextInput::make('chemical_name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Enter the generic/chemical name')
                            ->helperText('Active ingredient or generic name'),

                        Forms\Components\Toggle::make('doctor_prescription')
                            ->label('Requires Doctor Prescription')
                            ->helperText('Toggle if a doctor\'s prescription is required')
                            ->default(false),
                    ]),

                // ────────────────────────────────────────────
                // 2. DOSAGE FORM & STRENGTH (NEW)
                // ────────────────────────────────────────────
                Section::make('Dosage Form & Strength')
                    ->description('Physical form, concentration, and administration route')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('dosage_form')
                                    ->label('Dosage Form')
                                    ->options([
                                        'Tablet'          => 'Tablet',
                                        'Capsule'         => 'Capsule',
                                        'Syrup'           => 'Syrup',
                                        'Suspension'      => 'Suspension',
                                        'Injection'       => 'Injection',
                                        'IV Infusion'     => 'IV Infusion',
                                        'Cream'           => 'Cream',
                                        'Ointment'        => 'Ointment',
                                        'Gel'             => 'Gel',
                                        'Eye Drop'        => 'Eye Drop',
                                        'Ear Drop'        => 'Ear Drop',
                                        'Nasal Spray'     => 'Nasal Spray',
                                        'Inhaler'         => 'Inhaler',
                                        'Patch'           => 'Patch',
                                        'Suppository'     => 'Suppository',
                                        'Powder'          => 'Powder',
                                        'Other'           => 'Other',
                                    ])
                                    ->searchable()
                                    ->placeholder('Select form')
                                    ->helperText('Physical form of the medicine'),

                                Forms\Components\TextInput::make('strength')
                                    ->label('Strength / Concentration')
                                    ->maxLength(100)
                                    ->placeholder('e.g. 500mg, 250mg/5ml, 0.5%')
                                    ->helperText('Amount of active ingredient'),

                                Forms\Components\Select::make('route_of_administration')
                                    ->label('Route of Administration')
                                    ->options([
                                        'Oral'        => 'Oral (By mouth)',
                                        'IV'          => 'Intravenous (IV)',
                                        'IM'          => 'Intramuscular (IM)',
                                        'SC'          => 'Subcutaneous (SC)',
                                        'Topical'     => 'Topical (Skin)',
                                        'Inhaled'     => 'Inhaled',
                                        'Sublingual'  => 'Sublingual (Under tongue)',
                                        'Rectal'      => 'Rectal',
                                        'Ophthalmic'  => 'Ophthalmic (Eye)',
                                        'Otic'        => 'Otic (Ear)',
                                        'Nasal'       => 'Nasal',
                                        'Transdermal' => 'Transdermal (Patch)',
                                        'Other'       => 'Other',
                                    ])
                                    ->searchable()
                                    ->placeholder('Select route')
                                    ->helperText('How the medicine is administered'),
                            ]),
                    ]),

                // ────────────────────────────────────────────
                // 3. CATEGORIES
                // ────────────────────────────────────────────
                Section::make('Categories')
                    ->description('Assign this medicine to therapeutic categories')
                    ->schema([
                        Forms\Components\Select::make('categories')
                            ->label('Medicine Categories')
                            ->relationship('categories', 'name')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required()->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn(Forms\Set $set, ?string $state) => $set('slug', Str::slug($state))),
                                Forms\Components\TextInput::make('slug')->required()->maxLength(255),
                                Forms\Components\Textarea::make('description')->rows(3),
                            ])
                            ->placeholder('Select or create categories'),
                    ]),

                // ────────────────────────────────────────────
                // 4. PREGNANCY & LACTATION (NEW)
                // ────────────────────────────────────────────
                Section::make('Pregnancy & Lactation Safety')
                    ->description('Safety classification for pregnant and breastfeeding patients')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('pregnancy_category')
                                    ->label('FDA Pregnancy Category')
                                    ->options([
                                        'A' => 'Category A — Safe (Adequate studies show no risk)',
                                        'B' => 'Category B — Probably Safe (Animal studies OK, no human studies)',
                                        'C' => 'Category C — Use with Caution (Risk not ruled out)',
                                        'D' => 'Category D — Unsafe (Evidence of risk, benefits may outweigh)',
                                        'X' => 'Category X — Contraindicated (Risk clearly outweighs benefit)',
                                        'N' => 'Category N — Not Classified',
                                    ])
                                    ->placeholder('Select pregnancy category')
                                    ->helperText('FDA classification of risk during pregnancy'),

                                Forms\Components\Textarea::make('lactation_safety')
                                    ->label('Breastfeeding / Lactation Safety')
                                    ->rows(4)
                                    ->placeholder('Describe safety for breastfeeding mothers...')
                                    ->helperText('နို့တိုက်မိခင်များ သောက်လို့ရ/မရ အချက်အလက်'),
                            ]),
                    ]),

                // ────────────────────────────────────────────
                // 5. ADDITIONAL INFORMATION
                // ────────────────────────────────────────────
                Section::make('Additional Information')
                    ->description('Mechanism of action and general cautions')
                    ->schema([
                        Forms\Components\Textarea::make('moa')
                            ->label('Mechanism of Action (MOA)')
                            ->rows(4)
                            ->placeholder('Describe how this medicine works in the body'),

                        Forms\Components\Textarea::make('caution')
                            ->label('Caution & Warnings')
                            ->rows(4)
                            ->placeholder('Enter any cautions, warnings, or general safety information'),
                    ]),

                // ────────────────────────────────────────────
                // 6. ADVANCED MEDICAL INFORMATION
                // ────────────────────────────────────────────
                Section::make('Advanced Medical Information')
                    ->description('Detailed clinical information for medical professionals')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\Textarea::make('drug_interactions')
                                    ->label('Drug Interactions')
                                    ->rows(4)
                                    ->placeholder('List medications this drug should NOT be combined with')
                                    ->helperText('တခြား ဘယ်ဆေးတွေနဲ့ တွဲမသောက်သင့်ဘူးဆိုတဲ့ '),

                                Forms\Components\Textarea::make('storage_conditions')
                                    ->label('Storage Conditions')
                                    ->rows(4)
                                    ->placeholder('e.g. Store below 25°C, away from light')
                                    ->helperText('အပူချိန် ဘယ်လောက်မှာ သိမ်းဆည်းရမလဲ'),

                                Forms\Components\Textarea::make('overdose_emergency')
                                    ->label('Overdose Emergency Treatment')
                                    ->rows(4)
                                    ->placeholder('First-aid steps in case of overdose')
                                    ->helperText('ဆေးလွန်သွားပါက ကနဦးပြုစုရမည့် လမ်းညွှန်ချက်'),

                                Forms\Components\Textarea::make('contraindications')
                                    ->label('Contraindications')
                                    ->rows(4)
                                    ->placeholder('Conditions or groups who must NOT use this medicine')
                                    ->helperText('လုံးဝ မသုံးသင့်သည့်ရောဂါအခံများ (ဥပမာ ကိုယ်ဝန်ဆောင်)'),
                            ]),
                    ]),

                // ────────────────────────────────────────────
                // 7. EFFECTS & SIDE EFFECTS
                // ────────────────────────────────────────────
                Section::make('Effects & Side Effects')
                    ->description('Therapeutic effects and potential side effects')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('effects')
                                    ->label('Therapeutic Effects')
                                    ->relationship('effects', 'name')
                                    ->multiple()->searchable()->preload()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')->label('မြန်မာနာမည် (အတို)')->required()->maxLength(100)->placeholder('ဥပမာ: အဖျားကျ'),
                                        Forms\Components\TextInput::make('slug')->required()->unique()->maxLength(255)->placeholder('fever-reduction'),
                                        Forms\Components\Textarea::make('text')->rows(3)->placeholder('ဖော်ပြချက်'),
                                    ])
                                    ->placeholder('Therapeutic Effects ရွေးပါ'),

                                Forms\Components\Select::make('sideEffects')
                                    ->label('Side Effects')
                                    ->relationship('sideEffects', 'name')
                                    ->multiple()->searchable()->preload()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')->label('မြန်မာနာမည် (အတို)')->required()->maxLength(100)->placeholder('ဥပမာ: ပျို့ချင်ခြင်း'),
                                        Forms\Components\TextInput::make('slug')->required()->unique()->maxLength(255)->placeholder('nausea'),
                                        Forms\Components\Textarea::make('text')->rows(3)->placeholder('ဖော်ပြချက်'),
                                    ])
                                    ->placeholder('Side Effects ရွေးပါ'),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('ဆေးအမည်')
                    ->searchable()->sortable()->weight('bold'),

                Tables\Columns\TextColumn::make('chemical_name')
                    ->label('Generic Name')
                    ->searchable()->sortable()->color('gray')->size('sm'),

                Tables\Columns\TextColumn::make('dosage_form')
                    ->label('ဆေးပုံစံ')
                    ->badge()
                    ->color('gray')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('strength')
                    ->label('strength')
                    ->color('gray')->size('sm')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('route_of_administration')
                    ->label('Route')
                    ->badge()
                    ->color('info')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('brand.name')
                    ->label('Brand')
                    ->searchable()->sortable()->badge()->color('primary')->placeholder('မရှိ'),

                Tables\Columns\IconColumn::make('doctor_prescription')
                    ->label('Rx')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('danger')->falseColor('success'),

                Tables\Columns\TextColumn::make('pregnancy_category')
                    ->label('ကိုယ်ဝန်')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'A' => 'success',
                        'B' => 'info',
                        'C' => 'warning',
                        'D', 'X' => 'danger',
                        default => 'gray',
                    })
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('categories_count')
                    ->label('Categories')
                    ->counts('categories')->badge()->color('primary'),

                Tables\Columns\TextColumn::make('adult_dosages_count')
                    ->label('Adult Dose')
                    ->counts('adultDosages')->badge()->color('success')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('pediatric_dosages_count')
                    ->label('Pediatric Dose')
                    ->counts('pediatricDosages')->badge()->color('warning')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('aliases_count')
                    ->label('Aliases')
                    ->counts('aliases')->badge()->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('effects_count')
                    ->label('Effects')
                    ->counts('effects')->badge()->color('success')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('side_effects_count')
                    ->label('Side Effects')
                    ->counts('sideEffects')->badge()->color('danger')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')->dateTime()->sortable()->size('sm')->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')->dateTime()->sortable()->size('sm')->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('dosage_form')
                    ->label('Dosage Form')
                    ->options([
                        'Tablet' => 'Tablet',
                        'Capsule' => 'Capsule',
                        'Syrup' => 'Syrup',
                        'Suspension' => 'Suspension',
                        'Injection' => 'Injection',
                        'Cream' => 'Cream',
                        'Inhaler' => 'Inhaler',
                        'Other' => 'Other',
                    ])
                    ->placeholder('All Forms'),

                SelectFilter::make('pregnancy_category')
                    ->label('Pregnancy Category')
                    ->options(['A' => 'A', 'B' => 'B', 'C' => 'C', 'D' => 'D', 'X' => 'X', 'N' => 'N'])
                    ->placeholder('All Categories'),

                SelectFilter::make('brand_id')
                    ->label('Brand')
                    ->relationship('brand', 'name')
                    ->searchable()->preload()->placeholder('All Brands'),

                TernaryFilter::make('doctor_prescription')
                    ->label('Prescription Required')
                    ->placeholder('All Medicines')
                    ->trueLabel('Prescription Required')
                    ->falseLabel('Over the Counter'),

                SelectFilter::make('categories')
                    ->label('Category')
                    ->relationship('categories', 'name')
                    ->searchable()->preload()->placeholder('All Categories'),

                SelectFilter::make('effects')
                    ->label('Effects')
                    ->relationship('effects', 'name')
                    ->searchable()->preload()->placeholder('All Effects'),

                SelectFilter::make('sideEffects')
                    ->label('Side Effects')
                    ->relationship('sideEffects', 'name')
                    ->searchable()->preload()->placeholder('All Side Effects'),
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
            ->defaultSort('name', 'asc');
    }

    public static function getRelations(): array
    {
        return [
            CategoriesRelationManager::class,
            AliasesRelationManager::class,
            AdultDosagesRelationManager::class,
            PediatricDosagesRelationManager::class,
            EffectsRelationManager::class,
            SideEffectsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListMedicines::route('/'),
            'create' => Pages\CreateMedicine::route('/create'),
            'view'   => Pages\ViewMedicine::route('/{record}'),
            'edit'   => Pages\EditMedicine::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'chemical_name', 'brand.name'];
    }
}
