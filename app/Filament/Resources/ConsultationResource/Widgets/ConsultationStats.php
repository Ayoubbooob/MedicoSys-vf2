<?php

namespace App\Filament\Resources\ConsultationResource\Widgets;

// use App\Models\appointment;

use App\Models\appointment;
use App\Models\consultation;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class ConsultationStats extends BaseWidget
{
    protected function getCards(): array
    {
        $oneMonthAgo = Carbon::now()->subMonth();

        $currentMonthStart = Carbon::now()->startOfMonth();
        $previousMonthStart = Carbon::now()->subMonth()->startOfMonth();

        $currentMonthNewConsultations = Consultation::where('created_at', '>=', $currentMonthStart)->count();
        $previousMonthNewConsultations = Consultation::whereBetween('created_at', [$previousMonthStart, $currentMonthStart])->count();

        $isIncrease = false; // Initialize the variable as false

        $percentageIncrease = 0;
        if($previousMonthNewConsultations !==0) {
            $percentageIncrease = ($currentMonthNewConsultations - $previousMonthNewConsultations) / $previousMonthNewConsultations * 100;
        }
        $percentageIncrease = intval($percentageIncrease);
        if ($percentageIncrease > 0) {
            $isIncrease = true; // Set to true if there is an increase
        }
        return [
            Card::make('Total des Consultations', Consultation::count()),
            Card::make('Nouvelles Consultations', Consultation::where('created_at', '>', $oneMonthAgo)->count())
                ->color($isIncrease ? 'success' : 'danger')
                ->chart([2, 10, 3, 12, 1, 14, 10, 1, 2, 10])
                ->description($isIncrease ? 'Une augmentation de ' . $percentageIncrease . '%' : 'Une diminution de ' . abs($percentageIncrease) . '%')
                ->descriptionIcon($isIncrease ? 'heroicon-s-trending-up' : 'heroicon-s-trending-down'),
            Card::make('Rendez-vous annulés', appointment::where('status', 'annulé')->count())
                ->color('danger')
                ->chart([2, 10, 3, 12, 1, 14, 10, 1, 2, 10])
        ];
    }
}
