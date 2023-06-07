<?php

namespace App\Filament\Resources\DynamicBlockResource\Pages;

use App\Filament\Resources\DynamicBlockResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewDynamicBlock extends ViewRecord
{
    protected static string $resource = DynamicBlockResource::class;

    protected static ?string $title = 'Ressource';

    protected static ?string $breadcrumb = 'Vue';


    protected function getActions(): array
    {
        return [
            Actions\EditAction::make()->label('Modifier'),
        ];
    }
}
