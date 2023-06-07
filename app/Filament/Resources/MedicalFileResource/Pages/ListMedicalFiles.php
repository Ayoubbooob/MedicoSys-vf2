<?php

namespace App\Filament\Resources\MedicalFileResource\Pages;

use App\Filament\Resources\MedicalFileResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMedicalFiles extends ListRecords
{
    protected static string $resource = MedicalFileResource::class;
    protected function getHeaderWidgets(): array
    {
        return [
            MedicalFileResource\Widgets\MedicalFileStats::class,
        ];
    }
    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
