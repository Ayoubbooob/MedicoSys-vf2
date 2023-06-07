<?php

namespace App\Filament\Widgets\Dashboard;

use App\Models\appointment;
use App\Models\medical_file;
use App\Models\patient;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Illuminate\Support\Carbon;

class StatsOverview extends BaseWidget
{

    protected static ?int $sort = 0;

    protected function getCards(): array
    {


        //**********For Patient ***************
        $currentMonthStart = Carbon::now()->startOfMonth();
        $previousMonthStart = Carbon::now()->subMonth()->startOfMonth();

        $currentMonthNewPatients = Patient::where('created_at', '>=', $currentMonthStart)->count();
        $previousMonthNewPatients = Patient::whereBetween('created_at', [$previousMonthStart, $currentMonthStart])->count();

        $isIncreaseForPatient = false; // Initialize the variable as false

        $percentageIncreaseForPatient = 0 ;
        if ($previousMonthNewPatients !== 0) {
            $percentageIncreaseForPatient = ($currentMonthNewPatients - $previousMonthNewPatients) / $previousMonthNewPatients * 100;
        }

        $percentageIncreaseForPatient = intval($percentageIncreaseForPatient);
        if ($percentageIncreaseForPatient > 0) {
            $isIncreaseForPatient = true; // Set to true if there is an increase
        }

        /****************For Medical File***************/


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

        $cards = [
            Card::make('Total Patients', patient::count())
                ->icon('heroicon-o-users')
                //->description('le développement des patients dans le système')
                ->description($isIncreaseForPatient ? 'Une augmentation de '.$percentageIncreaseForPatient. '%' : 'Une diminution de '. abs($percentageIncreaseForPatient) . '%')
                ->descriptionIcon($isIncreaseForPatient ? 'heroicon-s-trending-up' : 'heroicon-s-trending-down')

                //->descriptionIcon('heroicon-o-trending-up')
                //->descriptionColor('success')
                ->color($isIncreaseForPatient ? 'success' : 'danger')

                ->chart([2, 10, 3, 12, 1, 14, 10, 1, 2, 10])
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),
            Card::make('Total Dossiers Medicaux', medical_file::count())
                ->icon('heroicon-o-document')
//                ->description('le développement des dossiers medicaux dans le système')
//                ->descriptionIcon('heroicon-o-trending-up')
                ->color($isIncrease ? 'success' : 'danger')
                ->description($isIncrease ? 'Une augmentation de ' . $percentageIncrease . '%' : 'Une diminution de ' . abs($percentageIncrease) . '%')
                ->descriptionIcon($isIncrease ? 'heroicon-s-trending-up' : 'heroicon-s-trending-down')
//                ->descriptionColor('success')
//                ->color('success')
                ->chart([2, 9, 8, 3, 7, 6, 10, 5, 6, 4])
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),

            Card::make('Rendez-vous confirmés', appointment::where('status', 'confirmé')->count())
                ->color('success')
                ->chart([2, 10, 3, 12, 1, 14, 10, 1, 2, 10])
        ];
        $indexToRemove = array_search('Filament GitHub', array_column($cards, 'label'));
        if ($indexToRemove !== false) {
            unset($cards[$indexToRemove]);
        }

        return array_values($cards);
    }
}
