<?php

namespace App\Filament\Resources;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use App\Filament\Resources\ConsultationResource\Pages;
use App\Filament\Resources\ConsultationResource\RelationManagers;
use App\Models\Consultation;
use App\Models\doctor;
use App\Models\medical_file;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;


class ConsultationResource extends Resource
{

    protected static ?string $navigationGroup = 'Gestion médicale';

    protected static ?string $model = Consultation::class;

    protected static ?string $navigationIcon = 'heroicon-o-check';

    public static ?string $label = 'Consultation';

    public static ?string $slug = '/consultation';


    protected static ?string $activeNavigationIcon = 'heroicon-o-check';


    protected static ?string $breadcrumb = 'Consultations';



    protected static ?string $navigationLabel = 'Consultations'; //side bar

    protected static ?string $pluralLabel = 'Consultations';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Select::make('medical_file_id')->required()
                            ->options(medical_file::all()->mapWithKeys(function ($medical_file) {
                                return [$medical_file->id => "{$medical_file->patient->first_name} {$medical_file->patient->last_name} - {$medical_file->ppr}"];
                            })),
                        Select::make('doctor_id')->required()
                            ->options(doctor::all()->mapWithKeys(function ($doctor) {
                                return [$doctor->id => "{$doctor->first_name} {$doctor->last_name} - {$doctor->cin}"];
                            })),
                        DateTimePicker::make('consultation_date')->required(),
                        Repeater::make('rapport_du_consultation')
                            ->schema([
                                MarkdownEditor::make('rapport du consultation')
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
                                FileUpload::make('images')
                                    ->label('documents')
                                    ->image()->multiple(),

                            ])
                    ])
                    ->columnSpan(['lg' => fn (?Consultation $record) => $record === null ? 3 : 2]),
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Placeholder::make('created_at')
                            ->label('Créé à')
                            ->content(fn (Consultation $record): ?string => $record->created_at?->diffForHumans()),

                        Forms\Components\Placeholder::make('updated_at')
                            ->label('Dernière mise à jour')
                            ->content(fn (Consultation $record): ?string => $record->updated_at?->diffForHumans()),
                    ])
                    ->columnSpan(['lg' => 1])
                    ->hidden(fn (?Consultation $record) => $record === null),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('medical_file.patient.first_name')
                    ->label('Prenom Patient')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('medical_file.patient.last_name')
                    ->label('Nom Patient')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('medical_file.patient.num')
                    ->label('Téléphone Patient')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('medical_file.patient.cin')
                    ->label('Cin Patient')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('doctor.first_name')
                    ->label('Prénom Docteur')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('doctor.last_name')
                    ->label('Nom Docteur')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('consultation_date')
                    ->label('Date de consultation')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Date de création')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('updated_at')
                    ->label('Date de modification')
                    ->dateTime()
                    ->sortable()
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
                            $indicators['créée depuis'] = 'Consulation from ' . Carbon::parse($data['créée depuis'])->toFormattedDateString();
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
                            $indicators['modifiée depuis'] = 'Consultation from ' . Carbon::parse($data['modifiée depuis'])->toFormattedDateString();
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListConsultations::route('/'),
            'create' => Pages\CreateConsultation::route('/create'),
            'edit' => Pages\EditConsultation::route('/{record}/edit'),
            'view' => Pages\ViewConsultation::route('/{record}'),
        ];
    }
}
