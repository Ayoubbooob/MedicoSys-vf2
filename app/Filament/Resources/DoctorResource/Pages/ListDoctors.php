<?php

namespace App\Filament\Resources\DoctorResource\Pages;

use App\Filament\Resources\DoctorResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDoctors extends ListRecords
{
    protected static string $resource = DoctorResource::class;
    // protected static ?string $title = 'du Consultation';

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Nouveau Médecin'),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            DoctorResource\Widgets\DoctorStats::class,
        ];
    }
}
