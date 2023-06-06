<?php

namespace App\Filament\Resources\MedicalFileResource\RelationManagers;

use App\Models\doctor;
use App\Models\medical_file;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ConsultationsRelationManager extends RelationManager
{
    protected static string $relationship = 'consultations';

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
//                Forms\Components\TextInput::make('id')
//                    ->required()
//                    ->maxLength(255),
//                Forms\Components\TextInput::make('rapport_du_consultation')
                //MarkdownEditor::make('rapport_du_consultation')

//                Select::make('medical_file_id')
//                    ->options(medical_file::all()->mapWithKeys(function ($medical_file) {
//                        return [$medical_file->id => "{$medical_file->patient->first_name} {$medical_file->patient->last_name} - {$medical_file->ppr}"];
//                    })),
                Select::make('doctor_id')
                    ->options(doctor::all()->mapWithKeys(function ($doctor) {
                        return [$doctor->id => "{$doctor->first_name} {$doctor->last_name} - {$doctor->cin}"];
                    }))->required(),
                DateTimePicker::make('consultation_date')->label("Date de consultation")->required(),
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
                //Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('consultation_date')->label('Date'),
                Tables\Columns\TextColumn::make('doctor.first_name')->label('Prénom Docteur'),
                Tables\Columns\TextColumn::make('doctor.last_name')->label('Nom Docteur'),
                Tables\Columns\TextColumn::make('medical_file.patient.first_name')->label('Prénom Patient'),
                Tables\Columns\TextColumn::make('medical_file.patient.last_name')->label('Nom Patient'),


            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()

//                    ->mutateFormDataUsing(function (array $data): array {
//                        $data['doctor_id'] = auth()->id();
//                        return $data;
//                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                //Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
