<?php

namespace App\Filament\Resources\PatientResource\Widgets;

use App\Models\appointment;
use App\Models\patient;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Illuminate\Support\Carbon;
use function Webmozart\Assert\Tests\StaticAnalysis\boolean;

class PatientStats extends BaseWidget
{
    protected function getCards(): array
    {

        $lastImcAverage = round(Patient::avg('last_imc'), 2);

        $oneMonthAgo = Carbon::now()->subMonth();

        $currentMonthStart = Carbon::now()->startOfMonth();
        $previousMonthStart = Carbon::now()->subMonth()->startOfMonth();

        $currentMonthNewPatients = Patient::where('created_at', '>=', $currentMonthStart)->count();
        $previousMonthNewPatients = Patient::whereBetween('created_at', [$previousMonthStart, $currentMonthStart])->count();

        $isIncrease = false; // Initialize the variable as false

        $percentageIncrease = ($currentMonthNewPatients - $previousMonthNewPatients) / $previousMonthNewPatients * 100;

        $percentageIncrease = intval($percentageIncrease);
        if ($percentageIncrease > 0) {
            $isIncrease = true; // Set to true if there is an increase
        }

        return [
            Card::make('Total Patients', patient::count()),
            Card::make('Nouveaux Patients', patient::where('created_at', '>', $oneMonthAgo)->count())
                ->color($isIncrease ? 'success' : 'danger')
                ->chart([2, 10, 3, 12, 1, 14, 10, 1, 2, 10])
            ->description($isIncrease ? 'Une augmentation de '.$percentageIncrease. '%' : 'Une diminution de '. abs($percentageIncrease) . '%')
        ->descriptionIcon($isIncrease ? 'heroicon-s-trending-up' : 'heroicon-s-trending-down'),


            Card::make('Moyenne IMC', $lastImcAverage)
        ];
    }
}
