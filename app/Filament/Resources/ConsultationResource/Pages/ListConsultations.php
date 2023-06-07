<?php

namespace App\Filament\Resources\ConsultationResource\Pages;

use App\Filament\Resources\ConsultationResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListConsultations extends ListRecords
{
    protected static string $resource = ConsultationResource::class;
    protected function getHeaderWidgets(): array
    {
        return [
            ConsultationResource\Widgets\ConsultationStats::class,
        ];
    }
    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
