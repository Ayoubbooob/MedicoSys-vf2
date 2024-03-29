<?php

namespace App\Filament\Resources\AppointmentRequestResource\Pages;

use App\Filament\Resources\AppointmentRequestResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAppointmentRequest extends EditRecord
{
    protected static string $resource = AppointmentRequestResource::class;

    protected static ?string $title = 'Affectation de statut de demande';

    protected static ?string $breadcrumb = 'Éditer'; // top app bar menu



    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make()->label('Supprimer'),
        ];
    }
}
