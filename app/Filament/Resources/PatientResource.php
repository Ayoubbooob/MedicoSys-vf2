<?php

namespace App\Filament\Resources;

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

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('first_name')->label('Prénom')->required()->maxLength(255),
                        TextInput::make('last_name')->label('Nom')->required()->maxLength(255),
                        TextInput::make('cin')->label('CIN')->required()->maxLength(255)->unique(),
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
                        TextInput::make('password')->label('Password')->maxLength(255)
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state)),
                        //                            ->required(fn (string $context): bool => $context === 'create'),


                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                //                TextColumn::make('ppr')->sortable()->searchable(),
                TextColumn::make('first_name')->sortable()->searchable(),
                TextColumn::make('last_name')->sortable()->searchable(),
                TextColumn::make('cin')->sortable()->searchable(),
                TextColumn::make('ppr')->sortable()->searchable(),
                TextColumn::make('num')->sortable(),
            ])
            ->filters([
                //
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
            'index' => Pages\ListPatients::route('/'),
            'create' => Pages\CreatePatient::route('/create'),
            'edit' => Pages\EditPatient::route('/{record}/edit'),
        ];
    }
}
