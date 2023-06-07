<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentRequestResource\Pages;
use App\Filament\Resources\AppointmentRequestResource\RelationManagers;
use App\Models\AppointmentRequest;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AppointmentRequestResource extends Resource
{
    protected static ?string $model = AppointmentRequest::class;

    protected static ?string $navigationGroup = 'Ressources mobiles';


    // demandeur de rendez-vous //page title
    protected static ?string $navigationIcon = 'heroicon-s-chat-alt-2';

    public static ?string $label='Demandes de rendez-vous'; //darurya // button new ...

    public static ?string $slug = '/demande-rdv';  //darurya

    //public static ?string $create = 'rendez-vous';



    protected static ?string $activeNavigationIcon = 'heroicon-s-chat-alt-2';



    protected static ?string $breadcrumb = 'Demandes Rendez-vous'; // // for menu //darurya



    protected static ?string $navigationLabel = 'Demandes Rendez-vous'; //side bar

    protected static ?string $pluralLabel = 'Demandes Rendez-vous'; // page name // //darurya


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                ->schema([
                TextInput::make('first_name')->label('Prénom')->disabled(),
                    TextInput::make('last_name')->label('Nom')->disabled(),
                    TextInput::make('cin')->disabled(),
                    TextInput::make('ppr')->disabled(),
                    TextInput::make('num')->label('Tel')->disabled(),
                    Select::make('status')
                        ->options([
                            'en cours' => 'en cours',
                            'confirmée' => 'confirmée',
                            'refusée' => 'refusée',
                            'en attente' => 'en attente'
                        ])->default('en cours')->autofocus(),
               Textarea::make('motif')->disabled(),
            ])->columns(2),

                Card::make()
                    ->schema([
                        Placeholder::make('created_at')->label('créé à')
                            ->content('Avant 7 jours'),
                        //                        ->content($createdAt
                        //                            ? Carbon::parse($createdAt)->diffForHumans() : ''),
                        Placeholder::make('updated_at')->label('dernière mise à jour')
                            ->content('Hier'),

                    ])

            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('first_name')->label('Prénom')->sortable()->searchable(),
                TextColumn::make('last_name')->label('Nom')->sortable()->searchable(),
                TextColumn::make('cin')->sortable()->searchable(),
                TextColumn::make('ppr')->sortable()->searchable(),
                TextColumn::make('num')->label('Tel')->sortable(),
                BadgeColumn::make('status')->searchable()
                    ->colors([
                        //                      'primary',// => 'en cours',
                        //                        'secondary' => 'confirmé',
                        'warning' => 'en attente',
                        'success' => 'confirmée',
                        'danger' => 'refusée',
                    ]),
                //
                TextColumn::make('created_at')->label("Date de la demande")->dateTime()->sortable(),
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
            'index' => Pages\ListAppointmentRequests::route('/'),
            //'create' => Pages\CreateAppointmentRequest::route('/create'),
            'edit' => Pages\EditAppointmentRequest::route('/{record}/edit'),
            'view' => Pages\ViewAppointmentRequest::route('/{record}'),

        ];
    }
}
