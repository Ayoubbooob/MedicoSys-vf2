<?php

namespace App\Filament\Resources\AppointmentRequestResource\Pages;

use App\Filament\Resources\AppointmentRequestResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAppointmentRequest extends ViewRecord
{
    protected static string $resource = AppointmentRequestResource::class;

    protected static ?string $title = 'Demande';

    protected static ?string $breadcrumb = 'Vue';

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make()->label('Modifier'),
        ];
    }

}
