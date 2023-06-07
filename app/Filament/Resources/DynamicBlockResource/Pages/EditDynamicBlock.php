<?php

namespace App\Filament\Resources\DynamicBlockResource\Pages;

use App\Filament\Resources\DynamicBlockResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDynamicBlock extends EditRecord
{
    protected static string $resource = DynamicBlockResource::class;

    protected static ?string $title = 'Modifier Ressource';

    protected static ?string $breadcrumb = 'Éditer';

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make()->label('Supprimer'),
        ];
    }
}
