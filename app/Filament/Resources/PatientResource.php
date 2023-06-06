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
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class PatientResource extends Resource
{
    protected static ?string $model = patient::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static ?string $label='Patient'; //darurya // button new ...

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
                        TextInput::make('last_imc')->label('IMC Actuel')->required(),


                        //**********Password will directly now hold cin@year_of_birth_date**************
//                        TextInput::make('password')->label('Password')->maxLength(255)
//                            ->password()
//                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
//                            ->dehydrated(fn ($state) => filled($state)),
                        //                            ->required(fn (string $context): bool => $context === 'create'),


                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {

        $lastBmi = Imc::select('bmi')
            ->orderBy('created_at', 'desc')
            ->limit(1)
            ->value('bmi');
        return $table
            ->columns([
//                TextColumn::make('id')->sortable(),
                TextColumn::make('first_name')->sortable()->searchable()->label('Prénom'),
                TextColumn::make('last_name')->sortable()->searchable()->label('Nom'),
                TextColumn::make('cin')->sortable()->searchable(),
                //TextColumn::make('ppr')->sortable()->searchable(),
                TextColumn::make('num')->sortable()->label('Tel'),
                TextColumn::make('email'),
                TextColumn::make('gender')->label('Genre'),
                TextColumn::make('last_imc')->label('Le dernier IMC'),

            ])
            ->filters([
                //
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
            //
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
