<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use Filament\Forms\Components\Card;
use App\Filament\Resources\AppointmentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Tables\Columns;
use Filament\Tables\Columns\TextColumn;

class ViewAppointment extends ViewRecord
{
    protected static string $resource = AppointmentResource::class;
}
