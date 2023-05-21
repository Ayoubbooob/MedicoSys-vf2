<?php

namespace App\Filament\Resources\AppointmentRequestResource\Pages;

use App\Filament\Resources\AppointmentRequestResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAppointmentRequest extends EditRecord
{
    protected static string $resource = AppointmentRequestResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
