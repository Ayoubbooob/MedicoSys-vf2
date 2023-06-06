<?php

namespace App\Filament\Resources\AppointmentResource\RelationManagers;

use App\Models\medical_file;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Actions\ViewAction;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MedicalFileRelationManager extends RelationManager
{
    protected static string $relationship = 'medical_file';

    protected static ?string $title = 'Dossier Médical';

    protected static ?string $recordTitleAttribute = 'medical_file_id';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('ppr')->label('PPR'),
                TextInput::make('patient.first_name')->label('Prénom'),
                TextInput::make('patient.last_name')->label('Nom'),
                TextInput::make('cin')->label('CIN'),
                TextInput::make('num')->label('Tel'),
                TextInput::make('email')->label('Email'),

//                >options(medical_file::all()->mapWithKeys(function ($medical_file) {
//                    return [$medical_file->id => "Patient: {$medical_file->patient->first_name} {$medical_file->patient->last_name} - PPR: {$medical_file->ppr}"];
//                }))
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('patient.first_name')->sortable()->searchable()->label('Prénom'),
                TextColumn::make('patient.last_name')->sortable()->searchable()->label('Nom'),
                TextColumn::make('patient.cin')->sortable()->searchable()->label('CIN'),
                TextColumn::make('patient.num')->sortable()->label('Tel'),
                TextColumn::make('patient.email')->sortable()->label('Email'),

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
                Tables\Actions\ViewAction::make()
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
