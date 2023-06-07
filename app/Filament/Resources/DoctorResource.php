<?php

namespace App\Filament\Resources;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use App\Filament\Resources\DoctorResource\Pages;
use App\Filament\Resources\DoctorResource\RelationManagers;
use App\Models\Doctor;
use App\Models\medical_file;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;

class DoctorResource extends Resource
{

    protected static ?string $navigationGroup = 'Gestion médicale';

    protected static ?string $model = Doctor::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static ?string $label = 'Médecin';

    public static ?string $slug = '/medecin';


    protected static ?string $activeNavigationIcon = 'heroicon-o-user';


    protected static ?string $breadcrumb = 'Médecins';



    protected static ?string $navigationLabel = 'Médecins'; //side bar

    protected static ?string $pluralLabel = 'Médecins';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Select::make('user_id')->label('L\'utilisateur concerné')
                            ->options(User::role('DOCTOR')->get()->mapWithKeys(function ($user) {
                                return [$user->id => "{$user->name}"];
                            })),
                        TextInput::make('first_name')->label('Prénom du Docteur'),
                        TextInput::make('last_name')->label('Nom du Docteur'),
                        TextInput::make('speciality')->label('Spécialité'),
                        TextInput::make('cin')->label('Cin'),
                    ])
                    ->columnSpan(['lg' => fn (?Doctor $record) => $record === null ? 3 : 2]),
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Placeholder::make('created_at')
                            ->label('Créé à')
                            ->content(fn (Doctor $record): ?string => $record->created_at?->diffForHumans()),

                        Forms\Components\Placeholder::make('updated_at')
                            ->label('Dernière mise à jour')
                            ->content(fn (Doctor $record): ?string => $record->updated_at?->diffForHumans()),
                    ])
                    ->columnSpan(['lg' => 1])
                    ->hidden(fn (?Doctor $record) => $record === null),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('first_name')->label('Prénom')->sortable()->searchable()
                    ->toggleable(),
                TextColumn::make('last_name')->label('Nom')->sortable()->searchable()
                    ->toggleable(),
                TextColumn::make('speciality')->label('Spécialité')->sortable()->searchable()
                    ->toggleable(),
                TextColumn::make('cin')->label('Cin')->sortable()->searchable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('créée depuis')
                            ->placeholder(fn ($state): string => 'Dec 18, ' . now()->subYear()->format('Y')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['créée depuis'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['créée depuis'] ?? null) {
                            $indicators['créée depuis'] = 'Doctor from ' . Carbon::parse($data['créée depuis'])->toFormattedDateString();
                        }
                        return $indicators;
                    }),
                Tables\Filters\Filter::make('updated_at')
                    ->form([
                        Forms\Components\DatePicker::make('modifiée depuis')
                            ->placeholder(fn ($state): string => 'Dec 18, ' . now()->subYear()->format('Y')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['modifiée depuis'],
                                fn (Builder $query, $date): Builder => $query->whereDate('updated_at', '>=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['modifiée depuis'] ?? null) {
                            $indicators['modifiée depuis'] = 'Doctor from ' . Carbon::parse($data['modifiée depuis'])->toFormattedDateString();
                        }
                        return $indicators;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),

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
            'index' => Pages\ListDoctors::route('/'),
            'create' => Pages\CreateDoctor::route('/create'),
            'edit' => Pages\EditDoctor::route('/{record}/edit'),
            'view' => Pages\ViewDoctor::route('/{record}'),
        ];
    }
}
