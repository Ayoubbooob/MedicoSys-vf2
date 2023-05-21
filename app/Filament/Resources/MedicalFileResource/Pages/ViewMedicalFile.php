<?php

namespace App\Filament\Resources\MedicalFileResource\Pages;

use App\Filament\Resources\MedicalFileResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMedicalFile extends ViewRecord
{
    protected static string $resource = MedicalFileResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
