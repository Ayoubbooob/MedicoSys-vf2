<?php

namespace App\Filament\Resources\AppointmentRequestResource\Widgets;

use App\Models\appointment;
use App\Models\AppointmentRequest;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class AppointmentRequestStats extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Total des Demandes', AppointmentRequest::count()),
            Card::make('Demandes confirmées', AppointmentRequest::where('status', 'confirmée')->count())
                ->color('success')
                ->chart([2, 10, 3, 12, 1, 14, 10, 1, 2, 10])
            ,
            Card::make('Demandes annulées', AppointmentRequest::where('status', 'refuseé')->count())
                ->color('danger')
                ->chart([2, 10, 3, 12, 1, 14, 10, 1, 2, 10])
        ];
    }
}
