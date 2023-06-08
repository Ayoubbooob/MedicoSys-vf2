<?php

namespace App\Filament\Resources;

use App\Models\appointment;
use App\Models\IMC;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use App\Filament\Resources\PatientResource\Pages;
use App\Filament\Resources\PatientResource\RelationManagers;
use App\Models\patient;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class PatientResource extends Resource
{
    protected static ?string $model = patient::class;

    protected static ?string $navigationGroup = 'Gestion médicale';

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static ?string $label = 'Patient'; //darurya // button new ...

    public static ?string $slug = '/patient';  //darurya

    //public static ?string $create = 'rendez-vous';



    protected static ?string $activeNavigationIcon = 'heroicon-o-user';



    protected static ?string $breadcrumb = 'Patients'; // // for menu //darurya



    protected static ?string $navigationLabel = 'Patients'; //side bar

    protected static ?string $pluralLabel = 'Patients'; // page name // //darurya


    //protected static ?string $pluralModelLabel = 'Rendez-vous';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('first_name')->label('Prénom')->required()->maxLength(255),
                        TextInput::make('last_name')->label('Nom')->required()->maxLength(255),
                        TextInput::make('cin')->label('CIN')->required()->maxLength(255)->unique(ignorable: fn ($record) => $record),
                        //                        TextInput::make('ppr')->label('PPR')->required()->maxLength(255)->unique(),
                        TextInput::make('num')->label('Numéro de téléphone')->required()->maxLength(255),
                        Select::make('gender')->label('Genre')
                            ->options([
                                'male' => 'Homme',
                                'female' => 'Femme',
                            ])
                            ->required(),
                        TextInput::make('email')->label('Email')->maxLength(255),
                        Select::make('marital_status')->label('Situation familiale')
                            ->options([
                                'marié' => 'Marié(e)',
                                'célibataire' => 'Célibataire',
                                'divorcé' => 'Divorcé(e)',
                                'veuf' => 'Veuf(veuve)',
                            ]),
                        DatePicker::make('birth_date'),
                        FileUpload::make('image')
                            ->label('Image')
                            ->image(),
                        TextInput::make('last_imc')->label('IMC Actuel')->required(),


                        //**********Password will directly now hold cin@year_of_birth_date**************
                        //                        TextInput::make('password')->label('Password')->maxLength(255)
                        //                            ->password()
                        //                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                        //                            ->dehydrated(fn ($state) => filled($state)),
                        //                            ->required(fn (string $context): bool => $context === 'create'),


                    ])->columnSpan(['lg' => fn (?Patient $record) => $record === null ? 3 : 2]),
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Placeholder::make('created_at')
                            ->label('Créé à')
                            ->content(fn (Patient $record): ?string => $record->created_at?->diffForHumans()),

                        Forms\Components\Placeholder::make('updated_at')
                            ->label('Dernière mise à jour')
                            ->content(fn (Patient $record): ?string => $record->updated_at?->diffForHumans()),
                    ])
                    ->columnSpan(['lg' => 1])
                    ->hidden(fn (?Patient $record) => $record === null),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {

        $lastBmi = Imc::select('bmi')
            ->orderBy('created_at', 'desc')
            ->limit(1)
            ->value('bmi');
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Image'),
                TextColumn::make('first_name')->sortable()->toggleable()->searchable()->label('Prénom'),
                TextColumn::make('last_name')->sortable()->toggleable()->searchable()->label('Nom'),
                TextColumn::make('cin')->sortable()->toggleable()->searchable(),
                TextColumn::make('num')->sortable()->toggleable()->label('Tel'),
                TextColumn::make('email')->toggleable(),
                TextColumn::make('gender')->toggleable()->label('Genre'),
                TextColumn::make('last_imc')->searchable()->toggleable()->label('Le dernier IMC'),
            ])
            ->filters([
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->placeholder(fn ($state): string => 'Dec 18, ' . now()->subYear()->format('Y')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['created_from'] ?? null) {
                            $indicators['created_from'] = 'Patient from ' . Carbon::parse($data['created_from'])->toFormattedDateString();
                        }
                        return $indicators;
                    }),
                Tables\Filters\Filter::make('updated_at')
                    ->form([
                        Forms\Components\DatePicker::make('updated_from')
                            ->placeholder(fn ($state): string => 'Dec 18, ' . now()->subYear()->format('Y')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['updated_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('updated_at', '>=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['updated_from'] ?? null) {
                            $indicators['updated_from'] = 'Patient from ' . Carbon::parse($data['updated_from'])->toFormattedDateString();
                        }
                        return $indicators;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                //Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make()->label(''),

            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            PatientResource\RelationManagers\MedicalFileRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPatients::route('/'),
            'create' => Pages\CreatePatient::route('/create'),
            'edit' => Pages\EditPatient::route('/{record}/edit'),
            'view' => Pages\ViewPatient::route('/{record}'),
        ];
    }
}
