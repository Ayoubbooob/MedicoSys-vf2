<?php

namespace App\Filament\Resources;

use Illuminate\Support\Facades\Request;
use Filament\Forms\Components\Repeater;
use App\Filament\Resources\AppointmentResource\Pages;
use App\Models\Appointment;
use Filament\Forms;
use Illuminate\Support\Facades\Auth;
use App\Models\doctor;
use App\Models\medical_file;
use Filament\Forms\Components\DateTimePicker;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Carbon;


class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {

        return $form
            ->schema([
                Select::make('medical_file_id')
                    ->options(medical_file::all()->mapWithKeys(function ($medical_file) {
                        return [$medical_file->id => "{$medical_file->patient->first_name} {$medical_file->patient->last_name} - {$medical_file->ppr}"];
                    })),
                Select::make('doctor_id')
                    ->options(Doctor::all()->mapWithKeys(function ($doctor) {
                        return [$doctor->id => "{$doctor->first_name} {$doctor->last_name} - {$doctor->cin}"];
                    })),
                DateTimePicker::make('appointment_date'),
                Textarea::make('motif'),
                Repeater::make('informations_supplementaires')
                    ->schema([
                        MarkdownEditor::make('informations_supplementaires')
                            ->enableToolbarButtons([
                                'attachFiles',
                                'bold',
                                'bulletList',
                                'codeBlock',
                                'edit',
                                'italic',
                                'link',
                                'orderedList',
                                'preview',
                                'strike',
                            ]),
                        FileUpload::make('image_url')
                            ->label('Image d\'examen')
                            ->image()->multiple(),

                    ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('medicalFile.patient.first_name')
                    ->label('Prenom Patient')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('medicalFile.patient.last_name')
                    ->label('Nom Patient')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('medicalFile.patient.num')
                    ->label('Telephone Patient')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('medicalFile.patient.cin')
                    ->label('Cin Patient')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('doctor.first_name')
                    ->label('Prenom Docteur')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('doctor.last_name')
                    ->label('Nom Docteur')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('appointment_date')
                    ->label('Date de RDV')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('motif')
                    ->label('Motif')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Date de creation')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('updated_at')
                    ->label('Date de Modification')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
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
                            $indicators['created_from'] = 'Appointment from ' . Carbon::parse($data['created_from'])->toFormattedDateString();
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
                            $indicators['updated_from'] = 'Appointment from ' . Carbon::parse($data['updated_from'])->toFormattedDateString();
                        }
                        return $indicators;
                    }),
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

    // public static function getEloquentQuery(): Builder
    // {
    //     $userId = Auth::id();
    //     $user = Auth::user();
    //     // if ($user->hasRole('MAJOR')) {
    //     // }
    //     if ($user->hasRole('DOCTOR')) {
    //         return parent::getEloquentQuery()
    //             ->join('doctors', 'doctor_id', '=', 'doctors.id')
    //             ->where('user_id', $userId);
    //     }
    //     return parent::getEloquentQuery();
    // }
    // public static function getEloquentQuery(): Builder
    // {
    //     $user = Auth::user();

    //     if ($user->hasRole('DOCTOR')) {
    //         return parent::getEloquentQuery()
    //             ->whereHas('doctor', function ($query) use ($user) {
    //                 $query->where('user_id', $user->id);
    //             })
    //             ->with(['doctor', 'medical_file.patient']);
    //     }

    //     return parent::getEloquentQuery();
    // }
    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();

        if ($user->hasRole('DOCTOR')) {
            return parent::getEloquentQuery()
                ->whereHas('doctor', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->with(['doctor', 'medicalFile.patient']);
        }

        return parent::getEloquentQuery();
    }






    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
            'edit' => Pages\EditAppointment::route('/{record}/edit'),
            'view' => Pages\ViewAppointment::route('/{record}'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
}
