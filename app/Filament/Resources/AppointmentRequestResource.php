<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentRequestResource\Pages;
use App\Filament\Resources\AppointmentRequestResource\RelationManagers;
use App\Models\AppointmentRequest;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AppointmentRequestResource extends Resource
{
    protected static ?string $model = AppointmentRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('first_name')->sortable()->searchable(),
                TextColumn::make('last_name')->sortable()->searchable(),
                TextColumn::make('cin')->sortable()->searchable(),
                TextColumn::make('ppr')->sortable()->searchable(),
                TextColumn::make('num')->sortable(),
                TextColumn::make('created_at')->label("Date de la demande")->dateTime()->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
//                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListAppointmentRequests::route('/'),
            'create' => Pages\CreateAppointmentRequest::route('/create'),
            'edit' => Pages\EditAppointmentRequest::route('/{record}/edit'),
        ];
    }
}
