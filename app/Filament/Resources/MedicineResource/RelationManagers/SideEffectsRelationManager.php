<?php

namespace App\Filament\Resources\MedicineResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SideEffectsRelationManager extends RelationManager
{
    protected static string $relationship = 'sideEffects';

    protected static ?string $title = 'Side Effects';

    protected static ?string $modelLabel = 'Side Effect';

    protected static ?string $pluralModelLabel = 'Side Effects';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->placeholder('side-effect-name')
                    ->helperText('Use lowercase with hyphens (e.g., nausea, drowsiness)'),
                
                Forms\Components\Textarea::make('text')
                    ->label('Description')
                    ->rows(4)
                    ->placeholder('Describe this side effect')
                    ->helperText('Optional: Detailed description of the side effect'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('slug')
            ->columns([
                Tables\Columns\TextColumn::make('slug')
                    ->label('Side Effect')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('text')
                    ->label('Description')
                    ->searchable()
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    })
                    ->placeholder('No description'),
                
                Tables\Columns\TextColumn::make('medicines_count')
                    ->label('Medicines')
                    ->counts('medicines')
                    ->badge()
                    ->color('warning'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
