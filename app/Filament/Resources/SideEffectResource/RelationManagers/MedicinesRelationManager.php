<?php

namespace App\Filament\Resources\SideEffectResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MedicinesRelationManager extends RelationManager
{
    protected static string $relationship = 'medicines';

    protected static ?string $title = 'Associated Medicines';

    protected static ?string $modelLabel = 'Medicine';

    protected static ?string $pluralModelLabel = 'Medicines';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('brand_id')
                    ->label('Brand')
                    ->relationship('brand', 'name')
                    ->searchable()
                    ->preload()
                    ->placeholder('Select a brand'),

                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Enter medicine name'),

                Forms\Components\TextInput::make('chemical_name')
                    ->label('Generic/Chemical Name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Enter generic name'),

                Forms\Components\Toggle::make('doctor_prescription')
                    ->label('Requires Doctor Prescription')
                    ->default(false),

                Forms\Components\Textarea::make('caution')
                    ->label('Caution & Warnings')
                    ->rows(3)
                    ->placeholder('Enter any cautions or warnings'),

                Forms\Components\Textarea::make('moa')
                    ->label('Mechanism of Action')
                    ->rows(3)
                    ->placeholder('Describe how this medicine works'),
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
                    ->searchable()
                    ->badge()
                    ->color('info')
                    ->placeholder('No Brand'),

                Tables\Columns\TextColumn::make('brand.company')
                    ->label('Company')
                    ->searchable()
                    ->color('gray')
                    ->size('sm')
                    ->placeholder('N/A'),

                Tables\Columns\IconColumn::make('doctor_prescription')
                    ->label('Rx Required')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('danger')
                    ->falseColor('success'),

                Tables\Columns\TextColumn::make('effects_count')
                    ->label('Effects')
                    ->counts('effects')
                    ->badge()
                    ->color('success'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('brand_id')
                    ->label('Brand')
                    ->relationship('brand', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\TernaryFilter::make('doctor_prescription')
                    ->label('Prescription Required')
                    ->placeholder('All Medicines')
                    ->trueLabel('Prescription Required')
                    ->falseLabel('Over the Counter'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->multiple(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(fn ($record) => "/admin/medicines/{$record->id}"),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name', 'asc');
    }
}
