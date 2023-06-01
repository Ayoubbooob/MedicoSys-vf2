<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MedicalFileResource\Pages;
use App\Filament\Resources\MedicalFileResource\RelationManagers;
use App\Models\MedicalFile;
use App\Models\medical_file;
use App\Models\patient;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
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
                        // Select::make('country_id')
                        // ->label('Country')
                        // ->options(Country::all()->pluck('name', 'id')->toArray())
                        // ->required()
                        // ->reactive()
                        // ->afterStateUpdated(fn (callable $set) => $set('state_id', null)),
                        Select::make('patient_id')
                            ->label('PPR')
                            ->options(patient::all()->pluck('ppr', 'id')->toArray())
                            ->required()
                            ->reactive(),
                        DatePicker::make('creation_date')->label("Date de creation")->required(),
                    ]),
                Card::make()
                    ->schema([
                        Repeater::make('dynamic_field')
                            ->schema([
                                TextInput::make('dynamic_field')->required(),
                            ])
                            ->createItemButtonLabel('Ajouter champs')

                    ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('patient.ppr')->sortable()->searchable(),
                TextColumn::make('patient.first_name')->sortable()->searchable(),
                TextColumn::make('patient.last_name')->sortable()->searchable(),
                TextColumn::make('creation_date')->dateTime(),
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
