<?php

namespace App\Filament\Widgets\Dashboard;

use App\Models\doctor;
use App\Models\medical_file;
use Filament\Widgets\PolarAreaChartWidget;

class MedicalFileUpdatedByDoctor extends PolarAreaChartWidget
{
    protected static ?string $heading = 'Chart';
    protected static ?int $sort=3;

    protected function getData(): array
    {
        // Retrieve the currently connected user
        $user = auth()->user();

        // Find the doctors associated with the connected user
        $doctors = Doctor::where('user_id', $user->id)->get();

        $labels = [];
        $values = [];

        foreach ($doctors as $doctor) {
            // Get the count of medical files updated by the doctor through the consultations table
            $medicalFileCount = $doctor->consultations()->distinct('medical_file_id')->count();

            // Append the doctor name and medical file count to the respective arrays
            $labels[] = "Dr. $doctor->first_name $doctor->last_name";
            $values[] = $medicalFileCount;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Number of Updated Medical Files',
                    'data' => $values,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.5)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 1,
                ],
            ],
        ];
    }
}
