<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentRequestResource\Pages;
use App\Filament\Resources\AppointmentRequestResource\RelationManagers;
use App\Models\AppointmentRequest;
use Carbon\Carbon;
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

    public static ?string $label = 'Demandes de rendez-vous'; //darurya // button new ...

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
                    ])->columnSpan(['lg' => fn (?AppointmentRequest $record) => $record === null ? 3 : 2]),
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Placeholder::make('created_at')
                            ->label('Créé à')
                            ->content(fn (AppointmentRequest $record): ?string => $record->created_at?->diffForHumans()),

                        Forms\Components\Placeholder::make('updated_at')
                            ->label('Dernière mise à jour')
                            ->content(fn (AppointmentRequest $record): ?string => $record->updated_at?->diffForHumans()),
                    ])
                    ->columnSpan(['lg' => 1])
                    ->hidden(fn (?AppointmentRequest $record) => $record === null),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('first_name')->label('Prénom')->sortable()->searchable()->toggleable(),
                TextColumn::make('last_name')->label('Nom')->sortable()->searchable()->toggleable(),
                TextColumn::make('cin')->sortable()->searchable()->toggleable(),
                TextColumn::make('ppr')->sortable()->searchable()->toggleable(),
                TextColumn::make('num')->label('Tel')->sortable()->toggleable(),
                BadgeColumn::make('status')->searchable()->toggleable()
                    ->colors([
                        'warning' => 'en attente',
                        'success' => 'confirmée',
                        'danger' => 'refusée',
                    ]),
                //
                TextColumn::make('created_at')->label("Date de la demande")->dateTime()->sortable(),
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
                            $indicators['created_from'] = 'AppointmentRequest from ' . Carbon::parse($data['created_from'])->toFormattedDateString();
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
                            $indicators['updated_from'] = 'AppointmentRequest from ' . Carbon::parse($data['updated_from'])->toFormattedDateString();
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
