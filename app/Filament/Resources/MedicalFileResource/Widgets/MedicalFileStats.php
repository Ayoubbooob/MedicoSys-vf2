<?php

namespace App\Filament\Resources\MedicalFileResource\Widgets;

// use App\Models\appointment;

use App\Models\appointment;
use App\Models\medical_file;
use App\Models\MedicalFile;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class MedicalFileStats extends BaseWidget
{
    protected function getCards(): array
    {
        $oneMonthAgo = Carbon::now()->subMonth();

        $currentMonthStart = Carbon::now()->startOfMonth();
        $previousMonthStart = Carbon::now()->subMonth()->startOfMonth();

        $currentMonthNewMedicalFiles = medical_file::where('created_at', '>=', $currentMonthStart)->count();
        $previousMonthNewMedicalFiles = medical_file::whereBetween('created_at', [$previousMonthStart, $currentMonthStart])->count();

        $isIncrease = false; // Initialize the variable as false

        $percentageIncrease = 0 ;
        if ($previousMonthNewMedicalFiles !== 0) {
            $percentageIncrease = ($currentMonthNewMedicalFiles - $previousMonthNewMedicalFiles) / $previousMonthNewMedicalFiles * 100;
        }
       // $percentageIncrease = ($currentMonthNewMedicalFiles - $previousMonthNewMedicalFiles) / $previousMonthNewMedicalFiles * 100;

        $percentageIncrease = intval($percentageIncrease);
        if ($percentageIncrease > 0) {
            $isIncrease = true; // Set to true if there is an increase
        }
        return [
            Card::make('Total des Dossiers médicaux', medical_file::count()),
            Card::make('Nouveaux Dossiers médicaux', medical_file::where('created_at', '>', $oneMonthAgo)->count())
                ->color($isIncrease ? 'success' : 'danger')
                ->chart([2, 10, 3, 12, 1, 14, 10, 1, 2, 10])
                ->description($isIncrease ? 'Une augmentation de ' . $percentageIncrease . '%' : 'Une diminution de ' . abs($percentageIncrease) . '%')
                ->descriptionIcon($isIncrease ? 'heroicon-s-trending-up' : 'heroicon-s-trending-down'),

        ];
    }
}
