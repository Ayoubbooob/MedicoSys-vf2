<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentResource\Pages;
use App\Models\Appointment;
use App\Models\doctor;
use App\Models\medical_file;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;




class AppointmentResource extends Resource
{

    public static ?string $label = 'Rendez-vous'; //darurya

    public static ?string $slug = '/rendez-vous';  //darurya

    //public static ?string $create = 'rendez-vous';

    protected static ?string $navigationGroup = 'Gestion médicale';




    protected static ?string $activeNavigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationIcon = 'heroicon-o-calendar';


    protected static ?string $model = Appointment::class;

    protected static ?string $breadcrumb = 'Rendez-vous'; // // for menu //darurya



    protected static ?string $navigationLabel = 'Rendez-vous'; //side bar

    protected static ?string $pluralLabel = 'Rendez-vous'; // page name // //darurya


    //protected static ?string $pluralModelLabel = 'Rendez-vous';



    public static function form(Form $form): Form
    {


        //        $createdAt = Appointment::query()
        //            ->select('created_at')
        //            ->first();
        //->get();

        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Select::make('medical_file_id')->required()
                            ->options(medical_file::all()->mapWithKeys(function ($medical_file) {
                                return [$medical_file->id => "Patient: {$medical_file->patient->first_name} {$medical_file->patient->last_name} - PPR: {$medical_file->ppr}"];
                            }))->label('Dossier médical'),
                        Select::make('doctor_id')->required()->label('Médecin')
                            ->options(Doctor::all()->mapWithKeys(function ($doctor) {
                                return [$doctor->id => "Médecin: Dr. {$doctor->first_name} {$doctor->last_name} - Cin: {$doctor->cin}"];
                            })),
                        DateTimePicker::make('appointment_date')->required()->label('Date Rendez-vous'),
                        Select::make('status')
                            ->options([
                                'en cours' => 'en cours',
                                'confirmé' => 'confirmé',
                                'annulé' => 'annulé',
                                'en attente' => 'en attente',
                            ])->default('en cours'),
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

                //->hidden(!Route::is('view'))

            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table

            ->columns([
                TextColumn::make('medical_file.patient.first_name')
                    ->label('Prenom Patient')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->size('lg')
                //                    ->copyable()
                //                    ->copyMessageDuration(1500)
                ,
                TextColumn::make('medical_file.patient.last_name')
                    ->label('Nom Patient')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->size('lg')
                //                    ->copyable()
                //                    ->copyMessageDuration(1500)
                ,
                TextColumn::make('doctor.first_name')
                    ->label('Prenom Docteur')
                    ->searchable()
                    ->sortable()
                    ->toggleable()->size('lg')
                //                    ->copyable()
                //                    ->copyMessageDuration(1500)
                ,
                TextColumn::make('doctor.last_name')
                    ->label('Nom Docteur')
                    ->sortable()
                    ->toggleable()->size('lg'),
                //                    ->copyable()
                //                    ->copyMessageDuration(1500),
                TextColumn::make('appointment_date')
                    ->label('Date de RDV')
                    ->dateTime()
                    ->sortable()
                    ->toggleable()->size('lg'),
                //                    ->copyable()
                //                    ->copyMessageDuration(1500),
                BadgeColumn::make('status')->searchable()
                    ->colors([
                        //                      'primary',// => 'en cours',
                        //                        'secondary' => 'confirmé',
                        'warning' => 'en attente',
                        'success' => 'confirmé',
                        'danger' => 'annulé',
                    ]),
                //                TextColumn::make('motif')

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
                //Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make()->label(''),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();

        if ($user->hasRole('DOCTOR')) {
            return parent::getEloquentQuery()
                ->whereHas('doctor', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->with(['doctor', 'medical_file.patient']);
        }

        return parent::getEloquentQuery();
    }




    public static function getRelations(): array
    {
        return [
            //            AppointmentResource\RelationManagers\PatientRelationManager::class,
            AppointmentResource\RelationManagers\DoctorRelationManager::class,

            AppointmentResource\RelationManagers\MedicalFileRelationManager::class,


        ];
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
}
