<?php

namespace App\Filament\Resources\PatientResource\Widgets;

use App\Models\appointment;
use App\Models\IMC;
use Carbon\Carbon;
use Filament\Widgets\LineChartWidget;
use Illuminate\Support\Facades\DB;

class PatientImcs extends LineChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        $patientId = request()->route('patient');

        $imcs = Imc::where('patient_id', $patientId)
            ->orderBy('created_at')
            ->get();

        $labels = $imcs->pluck('created_at')->map(function ($dateTime) {
            return substr($dateTime, 0, 10); // Extract only the date portion (YYYY-MM-DD)
        })->toArray();

        $values = $imcs->pluck('bmi')->toArray();

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Avancement du IMC du patient',
                    'data' => $values,
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'pointBackgroundColor' => 'rgba(255, 99, 132, 1)',
                    'pointBorderColor' => '#fff',
                    'pointHoverBackgroundColor' => '#fff',
                    'pointHoverBorderColor' => 'rgba(255, 99, 132, 1)',
                    'lineTension' => 0,
                ],
            ],
        ];
    }
}
