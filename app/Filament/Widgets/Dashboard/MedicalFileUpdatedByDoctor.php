<?php

namespace App\Filament\Widgets\Dashboard;

use App\Models\consultation;
use App\Models\doctor;
use App\Models\medical_file;
use App\Models\User;
use Filament\Widgets\PolarAreaChartWidget;

class MedicalFileUpdatedByDoctor extends PolarAreaChartWidget
{
    protected static ?string $heading = 'Activité des médecins';
    protected static ?int $sort=3;
//    protected static ?string $maxHeight = '200px';


    protected function getData(): array
    {
        // Retrieve the currently connected user
        //$user = auth()->user();

        // Find the doctors associated with the connected user
        //$doctors = Doctor::where('id', $user->id)->get();

        $doctors = Doctor::all();

        $labels = [];
        $values = [];

        $colorPalette = ['#36a2eb', '#ff6384', '#00ff00', '#ffff00', '#808080'];

        $backgroundColor= [];
        $borderColor= [];

        // Mask the values if needed
        $medicalFileCount = '';
        $maskedValues[] = $medicalFileCount > 0 ? '***' : 0;
        foreach ($doctors as $doctor) {
            // Get the count of medical files updated by the doctor through the consultations table
            $medicalFileCount = Consultation::where('doctor_id', $doctor->id)->distinct('medical_file_id')->count();

            // Append the doctor name and medical file count to the respective arrays
            $labels[] = "Dr. $doctor->first_name $doctor->last_name";
            $values[] = $medicalFileCount;

            // Assign colors from the color palette based on doctor index
            $colorIndex = $doctor->id % count($colorPalette);
            $backgroundColor[] = $colorPalette[$colorIndex];
            $borderColor[] = $this->darkenColor($colorPalette[$colorIndex], 0.3); // Example: darken the border color
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Mises à jour médicales',
                    'data' => $values,
                    'backgroundColor' => $backgroundColor,
                    'borderColor' => $borderColor,
                    'borderWidth' => 1,
                ],
            ],
            'maskedValues' => $maskedValues,
        ];
    }


    private function darkenColor($color, $amount)
    {
        // Implement your logic to darken the color here
        // Example: darken the color by reducing the RGB values by the specified amount
        // You can use libraries like ColorThief or write custom logic based on your requirements
        // Make sure to return the darkened color
        return $color;
    }
}
