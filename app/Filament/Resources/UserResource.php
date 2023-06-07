<?php

namespace App\Filament\Resources;

use Filament\Pages\Actions\CreateAction;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;


class UserResource extends Resource


{



    protected static ?string $navigationGroup = 'Administration des utilisateurs';

    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?int $navigationSort = 1;

    public static ?string $label = 'Utilisateur';

    public static ?string $slug = '/utilisateur';

    protected static ?string $breadcrumb = 'Utilisateurs';



    protected static ?string $navigationLabel = 'Utilisateurs'; //side bar

    protected static ?string $pluralLabel = 'Utilisateurs';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('name')
                            ->label('Nom Complet')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Adresse e-mail')
                            ->required()
                            ->maxLength(255),
                        //TO-DO: add Email validation(Regex pattern)
                        TextInput::make('tel')
                            ->tel(),
                        //->telRegex('[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/'),
                        TextInput::make('password')
                            ->label('Mot de passe')
                            ->password()
                            ->required(fn (Page $livewire): bool => $livewire instanceof CreateRecord)
                            ->minLength(8)
                            ->same('passwordConfirmation')
                            ->dehydrated(fn ($state) => filled($state))
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state)),
                        TextInput::make('passwordConfirmation')
                            ->password()
                            ->label('Confirmation de mot de passe')
                            ->required(fn (Page $livewire): bool => $livewire instanceof CreateRecord)
                            ->minLength(8)
                            ->dehydrated(false),
                        FileUpload::make('image')
                            ->label('Image')
                            ->image(),
                        Select::make('roles')
                            ->relationship('roles', 'name')
                            ->preload()->multiple(),
                        // Select::make('permissions')
                        //     ->multiple()
                        //     ->relationship('permissions', 'name')
                        //     ->preload()
                    ])
                    ->columnSpan(['lg' => fn (?User $record) => $record === null ? 3 : 2]),
                Card::make()
                    ->schema([
                        Forms\Components\Placeholder::make('created_at')
                            ->label('Créé à')
                            ->content(fn (User $record): ?string => $record->created_at?->diffForHumans()),

                        Forms\Components\Placeholder::make('updated_at')
                            ->label('Dernière mise à jour')
                            ->content(fn (User $record): ?string => $record->updated_at?->diffForHumans()),
                    ])
                    ->columnSpan(['lg' => 1])
                    ->hidden(fn (?User $record) => $record === null),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Image'),
                TextColumn::make('name')->label('Nom Complet')->searchable()->sortable()->toggleable(),
                TextColumn::make('email')->label('Adresse e-mail')->searchable()->sortable()->toggleable(),
                TextColumn::make('tel')->label('Téléphone')->sortable()->toggleable(),
                TextColumn::make('created_at')->label('Date de création')->dateTime()->sortable()->toggleable(),


            ])
            ->filters([
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('créé depuis')
                            ->placeholder(fn ($state): string => 'Dec 18, ' . now()->subYear()->format('Y')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['créé depuis'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['créé depuis'] ?? null) {
                            $indicators['créé depuis'] = 'User from ' . Carbon::parse($data['créé depuis'])->toFormattedDateString();
                        }
                        return $indicators;
                    }),
                Tables\Filters\Filter::make('updated_at')
                    ->form([
                        Forms\Components\DatePicker::make('modifié depuis')
                            ->placeholder(fn ($state): string => 'Dec 18, ' . now()->subYear()->format('Y')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['modifié depuis'],
                                fn (Builder $query, $date): Builder => $query->whereDate('updated_at', '>=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['modifié depuis'] ?? null) {
                            $indicators['modifié depuis'] = 'User from ' . Carbon::parse($data['modifié depuis'])->toFormattedDateString();
                        }
                        return $indicators;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
