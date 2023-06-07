<?php

namespace App\Filament\Resources\PatientResource\Pages;

use App\Filament\Resources\PatientResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\View\View;

class ViewPatient extends ViewRecord
{
    protected static string $resource = PatientResource::class;

    protected static ?string $title = 'Dossier électronique du patient';

    protected function getFooterWidgets() : array{
        return [
            PatientResource\Widgets\PatientImcs::class,
        ];
    }

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }


}
