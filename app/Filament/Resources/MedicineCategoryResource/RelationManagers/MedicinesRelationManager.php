<?php

namespace App\Filament\Resources\MedicineCategoryResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class MedicinesRelationManager extends RelationManager
{
    protected static string $relationship = 'medicines';

    protected static ?string $title = 'Medicines in this Category';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Medicine Name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('chemical_name')
                    ->label('Generic/Chemical Name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Medicine Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('chemical_name')
                    ->label('Generic Name')
                    ->searchable()
                    ->color('gray')
                    ->size('sm'),

                Tables\Columns\TextColumn::make('brand.name')
                    ->label('Brand')
                    ->badge()
                    ->color('info')
                    ->placeholder('No Brand'),

                Tables\Columns\IconColumn::make('doctor_prescription')
                    ->label('Rx Required')
                    ->boolean()
                    ->trueColor('danger')
                    ->falseColor('success'),
            ])
            ->filters([])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->label('Attach Medicine'),
            ])
            ->actions([
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}
