<?php

namespace App\Filament\Resources\PatientResource\RelationManagers;

use App\Models\patient;
use Filament\Forms;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MedicalFileRelationManager extends RelationManager
{
    protected static string $relationship = 'medical_file';

    protected static ?string $recordTitleAttribute = 'patient_id';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('ppr')->label('PPR')->required()->maxLength(255)
                            ->unique(ignorable: fn ($record) => $record),
                        //                        ->unique(),
                                DatePicker::make('created_at')->label("Date de creation")->required(),

                    ])
                    ->columns(2),

                Card::make()->label("Historique médical")
                    ->schema([
                        \Filament\Forms\Components\Builder::make('dynamic_fields')->label('Historique médical')
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
                                            ->label('Antécédents familiaux'),                                MarkdownEditor::make('antecedents_familiaux')
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
                TextColumn::make('ppr')->label('PPR')->sortable()->searchable(),
                TextColumn::make('created_at')->dateTime(),
                TextColumn::make('updated_at')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
