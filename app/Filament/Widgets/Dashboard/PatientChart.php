<?php

namespace App\Filament\Widgets\Dashboard;

use App\Models\Patient;
use Filament\Charts\DoughnutChart;
use Filament\Widgets\DoughnutChartWidget;
use Illuminate\Support\Facades\DB;

class PatientChart extends DoughnutChartWidget
{
    protected static ?int $sort = 1;

    protected static ?string $heading = 'Répartition des patients par situation familiale';

    protected function getData(): array
    {
        $maritalStatusCounts = Patient::query()
            ->select('marital_status', DB::raw('COUNT(*) as count'))
            ->groupBy('marital_status')
            ->get()
            ->pluck('count', 'marital_status')
            ->toArray();

        $labels = ['Veuf', 'Divorcé', 'Marié', 'Célibataire'];
        $values = [
            $maritalStatusCounts['veuf'] ?? 0,
            $maritalStatusCounts['divorcé'] ?? 0,
            $maritalStatusCounts['marié'] ?? 0,
            $maritalStatusCounts['célibataire'] ?? 0,
        ];

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Marital Status',
                    'data' => $values,
                    'backgroundColor' => ['#ff6384', '#36a2eb', '#ffce56', '#4bc0c0'],
                ],
            ],
        ];
    }

    // protected function chart(): DoughnutChartWidget
    // {
    //     return DoughnutChartWidget::make()
    //         ->responsive(true)
    //         ->options([
    //             // Additional chart options can be specified here
    //         ]);
    // }
}
