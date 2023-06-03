<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConsultationResource\Pages;
use App\Filament\Resources\ConsultationResource\RelationManagers;
use App\Models\Consultation;
use App\Models\doctor;
use App\Models\medical_file;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ConsultationResource extends Resource
{
    protected static ?string $model = Consultation::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('medical_file_id')
                    ->options(medical_file::all()->mapWithKeys(function ($medical_file) {
                        return [$medical_file->id => "{$medical_file->patient->first_name} {$medical_file->patient->last_name} - {$medical_file->ppr}"];
                    })),
                Select::make('doctor_id')
                    ->options(doctor::all()->mapWithKeys(function ($doctor) {
                        return [$doctor->id => "{$doctor->first_name} {$doctor->last_name} - {$doctor->cin}"];
                    })),
                DateTimePicker::make('consultation_date'),
                Repeater::make('rapport_du_consultation')
                    ->schema([
                        MarkdownEditor::make('rapport du consultation')
                            ->enableToolbarButtons([
                                'attachFiles',
                                'bold',
                                'bulletList',
                                'codeBlock',
                                'edit',
                                'italic',
                                'link',
                                'orderedList',
                                'preview',
                                'strike',
                            ]),
                        FileUpload::make('images')
                            ->label('documents')
                            ->image()->multiple(),

                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('medical_file.patient.first_name')
                    ->label('Prenom Patient')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('medical_file.patient.last_name')
                    ->label('Nom Patient')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('medical_file.patient.num')
                    ->label('Telephone Patient')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('medical_file.patient.cin')
                    ->label('Cin Patient')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('doctor.first_name')
                    ->label('Prenom Docteur')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('doctor.last_name')
                    ->label('Nom Docteur')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('consultation_date')
                    ->label('Date de consultation')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Date de creation')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('updated_at')
                    ->label('Date de Modification')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListConsultations::route('/'),
            'create' => Pages\CreateConsultation::route('/create'),
            'edit' => Pages\EditConsultation::route('/{record}/edit'),
        ];
    }
}
