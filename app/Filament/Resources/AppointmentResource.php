<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentResource\Pages;
use App\Filament\Resources\AppointmentResource\RelationManagers;
use App\Models\Appointment;
use Filament\Forms;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Filament\Forms\Components\DateTimePicker;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //

                TextInput::make('id'),
                TextInput::make('patient_id'),
                TextInput::make('doctor_id'),
                TextInput::make('major_id'),
                DateTimePicker::make('appointment_date'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            //->query(static::getModel()::query()->where('doctor_id', $userId))
                    ])
            ->columns([
                TextColumn::make('id')->searchable(),
                TextColumn::make('patient_id')->searchable(),
                TextColumn::make('doctor_id')->searchable(),
                TextColumn::make('major_id')->searchable(),
                TextColumn::make('appointment_date')->dateTime()->sortable(),
            ])
            ->filters([
                // Filter::make('is_featured')
                //     ->query(fn (Builder $query): Builder => $query->where('doctor_id', $userId))
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $userId = Auth::id();
        $user = Auth::user();
        if ($user->hasRole('Admin')) {
            return parent::getEloquentQuery();
        }
        return parent::getEloquentQuery()->join('doctors', 'appointments.doctor_id', '=', 'doctors.id')
            ->where('user_id', $userId);
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
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
            'edit' => Pages\EditAppointment::route('/{record}/edit'),
        ];
    }

}
