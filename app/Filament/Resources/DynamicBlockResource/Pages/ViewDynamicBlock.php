<?php

namespace App\Filament\Resources\DynamicBlockResource\Pages;

use App\Filament\Resources\DynamicBlockResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewDynamicBlock extends ViewRecord
{
    protected static string $resource = DynamicBlockResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
