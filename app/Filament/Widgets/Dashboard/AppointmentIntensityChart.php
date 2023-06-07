<?php

namespace App\Filament\Widgets\Dashboard;

use App\Models\appointment;
use Filament\Widgets\LineChartWidget;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AppointmentIntensityChart extends LineChartWidget
{


    protected static ?int $sort = 1;

    protected static ?string $heading = 'Répartition Rendez-vous';

    public ?string $filter = 'semaine';

    protected static ?string $maxHeight = '225px';




    protected function getFilters(): ?array
    {
        return [
            'semaine' => 'semaine',
            'mois' => 'mois',
        ];
    }

    protected function getData(): array
    {

        $activeFilter = $this->filter;

        if($activeFilter ==='semaine'){
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $appointments = Appointment::query()
            ->select('appointment_date', DB::raw('COUNT(*) as count'))
            ->whereBetween('appointment_date', [$startOfWeek, $endOfWeek])
            ->where('status', $value='confirmé')
            ->groupBy('appointment_date')
            ->orderBy('appointment_date')
            ->get();

        $labels = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
        $values = [];

        $currentDate = $startOfWeek;
        while ($currentDate <= $endOfWeek) {
            $dayOfWeek = $currentDate->format('N');
            $appointment = $appointments->first(function ($appointment) use ($currentDate) {
                return $appointment->appointment_date === $currentDate->format('Y-m-d H:i:s');
            });
            $values[$dayOfWeek] = $appointment ? $appointment->count : 0;
            $currentDate->addDay();
        }

    } elseif ($activeFilter === 'mois') {
            for ($month = 1; $month <= 12; $month++) {
                $startOfMonth = Carbon::create(null, $month, 1, 0, 0, 0);
                $endOfMonth = Carbon::create(null, $month, $startOfMonth->daysInMonth, 23, 59, 59);

                $appointments = Appointment::query()
                    ->select('appointment_date', DB::raw('COUNT(*) as count'))
                    ->whereBetween('appointment_date', [$startOfMonth, $endOfMonth])
                    ->where('status', 'confirmé')
                    ->groupBy('appointment_date')
                    ->get();

                $labels[] = $startOfMonth->format('F');
                $values[] = $appointments->sum('count');
            }
}

        $values = array_values($values);

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Nombre de rendez-vous',
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
