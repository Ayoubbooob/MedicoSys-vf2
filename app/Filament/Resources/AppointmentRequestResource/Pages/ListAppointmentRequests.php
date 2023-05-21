<?php

namespace App\Filament\Resources\AppointmentRequestResource\Pages;

use App\Filament\Resources\AppointmentRequestResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAppointmentRequests extends ListRecords
{
    protected static string $resource = AppointmentRequestResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
//            Actions\DeleteAction::make(),

        ];
    }
}
