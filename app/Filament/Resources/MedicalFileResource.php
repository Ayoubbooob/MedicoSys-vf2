<?php

namespace App\Filament\Resources;

use Filament\Forms\Components\Builder;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\MedicalFileResource\Pages;
use App\Models\medical_file;
use App\Models\patient;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MedicalFileResource extends Resource
{
    protected static ?string $model = medical_file::class;

    protected static ?string $navigationIcon = 'heroicon-o-document';

    public static ?string $label = 'Dossier médical';

    public static ?string $slug = '/dossiers-médicaux';


    protected static ?string $activeNavigationIcon = 'heroicon-o-document';


    protected static ?string $breadcrumb = 'Dossiers médicaux';



    protected static ?string $navigationLabel = 'Dossiers médicaux'; //side bar

    protected static ?string $pluralLabel = 'Dossiers médicaux';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('ppr')->label('PPR')->required()->maxLength(255)
                            ->unique(ignorable: fn ($record) => $record),
                        // Select::make('patient_id')
                        //     ->label('Patient')
                        //     ->options(patient::all()->pluck('cin', 'id')->toArray())
                        //     ->required()
                        //     ->reactive(),
                        Select::make('patient_id')->required()
                            ->options(patient::all()->mapWithKeys(function ($patient) {
                                return [$patient->id => "Patient: {$patient->first_name} {$patient->last_name} - Cin: {$patient->cin}"];
                            }))->label('Patient concerné'),
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
                    ->columnSpan(['lg' => fn (?medical_file $record) => $record === null ? 3 : 2]),
                Card::make()
                    ->schema([
                        Placeholder::make('created_at')
                            ->label('Créé à')
                            ->content(fn (medical_file $record): ?string => $record->created_at?->diffForHumans()),

                        Placeholder::make('updated_at')
                            ->label('Dernière mise à jour')
                            ->content(fn (medical_file $record): ?string => $record->updated_at?->diffForHumans()),
                    ])
                    ->columnSpan(['lg' => 1])
                    ->hidden(fn (?medical_file $record) => $record === null),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ppr')->label('PPR')->sortable()->searchable()->toggleable(),
                TextColumn::make('patient.last_name')->label('Nom')->toggleable()->sortable()->searchable(),
                TextColumn::make('patient.first_name')->label('Prénom')->toggleable()->sortable()->searchable(),
                TextColumn::make('patient.cin')->label('CIN')->toggleable()->sortable()->searchable(),
                TextColumn::make('patient.num')->label('Tel')->toggleable(),
                TextColumn::make('created_at')->label('Date de création')->dateTime()->toggleable(),
                TextColumn::make('updated_at')->label('Date de dernière modification')->dateTime()->toggleable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        DatePicker::make('créé depuis')
                            ->placeholder(fn ($state): string => 'Dec 18, ' . now()->subYear()->format('Y')),
                    ])
                    ->query(function (EloquentBuilder $query, array $data): EloquentBuilder {
                        return $query
                            ->when(
                                $data['créé depuis'],
                                fn (EloquentBuilder $query, $date): EloquentBuilder => $query->whereDate('created_at', '>=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['créé depuis'] ?? null) {
                            $indicators['créé depuis'] = 'Doctor from ' . Carbon::parse($data['créé depuis'])->toFormattedDateString();
                        }
                        return $indicators;
                    }),
                Tables\Filters\Filter::make('updated_at')
                    ->form([
                        DatePicker::make('modifié depuis')
                            ->placeholder(fn ($state): string => 'Dec 18, ' . now()->subYear()->format('Y')),
                    ])
                    ->query(function (EloquentBuilder $query, array $data): EloquentBuilder {
                        return $query
                            ->when(
                                $data['modifié depuis'],
                                fn (EloquentBuilder $query, $date): EloquentBuilder => $query->whereDate('updated_at', '>=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['modifié depuis'] ?? null) {
                            $indicators['modifié depuis'] = 'Doctor from ' . Carbon::parse($data['modifié depuis'])->toFormattedDateString();
                        }
                        return $indicators;
                    }),
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
            MedicalFileResource\RelationManagers\ConsultationsRelationManager::class
        ];
    }
    public static function getEloquentQuery(): EloquentBuilder
    {
        $user = Auth::user();

        if ($user->hasRole('DOCTOR')) {
            return parent::getEloquentQuery()
                ->whereHas('appointments', function ($query) use ($user) {
                    $query->whereHas('doctor', function ($query) use ($user) {
                        $query->where('user_id', $user->id);
                    });
                })
                ->with(['appointments.doctor', 'patient']);
        }

        return parent::getEloquentQuery();
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
