<?php

namespace App\Filament\Widgets\Dashboard;

use App\Models\appointment;
use App\Models\doctor;
use Filament\Widgets\BarChartWidget;
use Illuminate\Support\Facades\DB;

class AppointmentsByDoctor extends BarChartWidget
{
    protected static ?string $heading = 'Répartition Rendez-vous par médecin';
    protected static ?int $sort =1;
    protected static ?string $maxHeight = '360px';



    public ?int $doctorId = 1;


    function getDoctorNameById($doctorId) : string
    {
        $doctor = Doctor::find($doctorId);
        return "Dr. $doctor->first_name $doctor->last_name";
    }
    protected function getData(): array
    {
        $appointments = Appointment::query()
            ->select('doctor_id', DB::raw('COUNT(*) as count'))
            ->where('status', 'confirmé')
            ->groupBy('doctor_id')
            ->get();

        $doctors = $appointments->pluck('doctor_id')->toArray();
        $appointmentCounts = $appointments->pluck('count', 'doctor_id')->toArray();

        $labels = [];
        $values = [];

        foreach ($doctors as $doctorId) {
            $doctorName = $this->getDoctorNameById($doctorId); // Replace with your logic to retrieve the doctor name
            $labels[] = $doctorName;
            $appointmentCount = $appointmentCounts[$doctorId] ?? 0;
            $values[] = $appointmentCount;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Médecins',
                    'data' => $values,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.5)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 1,
                ],
            ],
        ];
    }


}
