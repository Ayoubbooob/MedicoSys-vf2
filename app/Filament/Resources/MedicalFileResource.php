<?php

namespace App\Filament\Resources;

use Filament\Forms\Components\Builder;
use App\Filament\Resources\MedicalFileResource\Pages;
use App\Filament\Resources\MedicalFileResource\RelationManagers;
use App\Models\MedicalFile;
use App\Models\medical_file;
use App\Models\patient;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\FileUpload;


use Illuminate\Database\Eloquent\SoftDeletingScope;

class MedicalFileResource extends Resource
{
    protected static ?string $model = medical_file::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                ->schema([
                    TextInput::make('ppr')->label('PPR')->required()->maxLength(255)
                        ->unique(ignorable: fn ($record) => $record),
//                        ->unique(),
                    Select::make('patient_id')
                    ->label('Patient')
                    ->options(patient::all()->pluck('cin', 'id')->toArray())
                    ->required()
                    ->reactive(),
//                    DatePicker::make('creation_date')->label("Date de creation")->required(),

                ])
                ->columns(2),

                Card::make()->label("Historique médical")
                    ->schema([
                            Builder::make('dynamic_fields')->label('Historique médical')
                        ->blocks([
                            Builder\Block::make('antecedents')->label('Antécédents')
    //                            ->statePath('antecedents')
                                ->schema([

                                    MarkdownEditor::make('antecedents_medicaux')
                                        ->label('Antécédents médicaux et facteurs de risque')
                                        ->required(),
                                    MarkdownEditor::make('allergies')
                                        ->label('Allergies et intolérances'),
                                    MarkdownEditor::make('antecedents_familiaux')
                                        ->label('Antécédents familiaux')
    ,                                MarkdownEditor::make('antecedents_familiaux')
                                        ->label('Antécédents familiaux'),
                                    MarkdownEditor::make('antecedents_chirurgicaux')
                                        ->label('Antécédents chirurgicaux et obstétricaux'),


                                ]),

                            Builder\Block::make('biometrie')->label('Biometrie')
    //                            ->statePath('antecedents')
                                ->schema([
                                    TextInput::make('poids')
                                        ->label('Poids	'),
                                    TextInput::make('taille')
                                        ->label('Taille'),
                                    TextInput::make('imc')
                                        ->label('IMC'),
                                    TextInput::make('groupe_sanguin')
                                        ->label('Groupe sanguin'),
                                    MarkdownEditor::make('indicateurs_biologiques')
                                        ->label('Indicateurs biologiques'),
                                ]),


                        Builder\Block::make('traitement_chronique')->label('Traitement Chronique')

                            ->schema([

                                DatePicker::make('date_traitement')->label('Date de traitement'),
                                MarkdownEditor::make('traitement')
                                    ->label('Traitement')
                            ]),

                        Builder\Block::make('Vaccination')->label('Vaccination')
                            ->schema([
                                DatePicker::make('date_vaccination')->label('Date'),
                                TextInput::make('vaccin')
                                    ->label('Vaccin	'),
                                TextInput::make('injection')
                                    ->label('Injection'),
                                TextInput::make('methode')
                                    ->label('Méthode '),
                                TextInput::make('lot')
                                    ->label('Lot'),
                                TextInput::make('resultat')
                                    ->label('Résultat'),
                                DatePicker::make('rappel')->label('Rappel'),
                            ]),

                        Builder\Block::make('examen_biologiques')->label('Examen Biologiques')
                            ->schema([
                                DatePicker::make('date_examen')->label('Date d\'examen'),
                                MarkdownEditor::make('detail_examen')
                                    ->label('Details d\'examen'),
                                FileUpload::make('image_url')
                                    ->label('Image d\'examen')
                                    ->image()->multiple(),

                            ]),
                        ]),

                    ])
                    ])
            ->columns(2);



    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
//                TextColumn::make('id')->sortable(),
                TextColumn::make('ppr')->label('PPR')->sortable()->searchable(),
                TextColumn::make('patient.last_name')->label('Nom')->sortable()->searchable(),
                TextColumn::make('patient.first_name')->label('Prénom')->sortable()->searchable(),
                TextColumn::make('patient.cin')->label('CIN')->sortable()->searchable(),
                TextColumn::make('patient.num')->label('Tel'),

                TextColumn::make('created_at')->dateTime(),
                TextColumn::make('updated_at')->dateTime(),
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
            'index' => Pages\ListMedicalFiles::route('/'),
            'create' => Pages\CreateMedicalFile::route('/create'),
            'edit' => Pages\EditMedicalFile::route('/{record}/edit'),
            'view' => Pages\ViewMedicalFile::route('/{record}'),

        ];
    }
}
