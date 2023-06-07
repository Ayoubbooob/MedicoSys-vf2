<?php

namespace App\Filament\Widgets\Dashboard;

use App\Models\Patient;
use Filament\Charts\BarChart;
use Filament\Widgets\BarChartWidget;
use Illuminate\Support\Facades\DB;

class PatientBarChart extends BarChartWidget
{
    protected static ?string $heading = 'Répartition Genre Patients ';
    protected static ?int $sort = 2;
    //protected static ?string $maxHeight = '50px';

    protected static ?string $maxHeight = '250px';


    protected int | string | array $columnSpan = 'full';


    protected function getData(): array
    {
        $genderCounts = Patient::query()
            ->select('gender', DB::raw('COUNT(*) as count'))
            ->groupBy('gender')
            ->get()
            ->pluck('count', 'gender')
            ->toArray();

        $labels = ['Masculin', 'Féminin'];
        $values = [
            $genderCounts['male'] ?? 0,
            $genderCounts['female'] ?? 0,
        ];

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Nombre par genre',
                    'data' => $values,
                    'backgroundColor' => ['#36a2eb', '#ff6384'],
                ],
            ],
        ];
    }
}
