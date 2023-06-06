<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Card;
use App\Filament\Resources\AppointmentResource;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Actions;
use Filament\Resources\Form;
use Filament\Resources\Pages\ViewRecord;
use Filament\Tables\Columns;
use Filament\Tables\Columns\TextColumn;

class ViewAppointment extends ViewRecord
{
    protected static string $resource = AppointmentResource::class;
    protected static ?string $title = 'Détails du Rendez-vous';


    //protected static ?string $slug = '';

    //protected ?string $heading = 'kjj';





}
