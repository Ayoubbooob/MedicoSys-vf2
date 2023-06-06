<?php

namespace App\Filament\Resources\AppointmentResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DoctorRelationManager extends RelationManager
{
    protected static string $relationship = 'doctor';

    protected static ?string $title = 'Médecin';

    protected static ?string $recordTitleAttribute = 'doctor_id';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('first_name')->label('Prénom'),
                Forms\Components\TextInput::make('last_name')->label('Nom'),
                Forms\Components\TextInput::make('speciality')->label('Specialité')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('first_name')->label('Prénom'),
                TextColumn::make('last_name')->label('Nom'),
                TextColumn::make('cin')->label('CIN'),
                TextColumn::make('speciality')->label('Spécialité'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),
                //Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
