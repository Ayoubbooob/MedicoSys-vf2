<?php

namespace App\Filament\Resources\DoctorResource\Pages;

use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Card;
use App\Filament\Resources\ConsultationResource;
use App\Filament\Resources\DoctorResource;
use App\Models\doctor;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Actions;
use Filament\Resources\Form;
use Filament\Resources\Pages\ViewRecord;
use Filament\Tables\Columns;
use Filament\Tables\Columns\TextColumn;

class ViewDoctor extends ViewRecord
{
    protected static string $resource = DoctorResource::class;
    protected static ?string $title = 'Détails du docteur';
}
