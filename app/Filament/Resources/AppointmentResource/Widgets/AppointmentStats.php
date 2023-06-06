<?php

namespace App\Filament\Resources\AppointmentResource\Widgets;

use App\Models\appointment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class AppointmentStats extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Total des Rendez-vous', appointment::count()),
            Card::make('Rendez-vous confirmés', appointment::where('status', 'confirmé')->count())
                ->color('success')
                ->chart([2, 10, 3, 12, 1, 14, 10, 1, 2, 10])
            ,
            Card::make('Rendez-vous annulés', appointment::where('status', 'annulé')->count())
                ->color('danger')
                ->chart([2, 10, 3, 12, 1, 14, 10, 1, 2, 10])
        ];
    }
}
