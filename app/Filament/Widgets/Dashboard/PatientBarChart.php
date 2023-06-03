<?php

namespace App\Filament\Widgets\Dashboard;

use App\Models\Patient;
use Filament\Charts\BarChart;
use Filament\Widgets\BarChartWidget;
use Illuminate\Support\Facades\DB;

class PatientBarChart extends BarChartWidget
{
    protected static ?string $heading = 'Répartition des patients par genre ';
    protected static ?int $sort = 1;

    protected function getData(): array
    {
        $genderCounts = Patient::query()
            ->select('gender', DB::raw('COUNT(*) as count'))
            ->groupBy('gender')
            ->get()
            ->pluck('count', 'gender')
            ->toArray();

        $labels = ['Male', 'Female'];
        $values = [
            $genderCounts['male'] ?? 0,
            $genderCounts['female'] ?? 0,
        ];

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Gender',
                    'data' => $values,
                    'backgroundColor' => ['#36a2eb', '#ff6384'],
                ],
            ],
        ];
    }
}
