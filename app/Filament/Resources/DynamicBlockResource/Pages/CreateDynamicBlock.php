<?php

namespace App\Filament\Resources\DynamicBlockResource\Pages;

use App\Filament\Resources\DynamicBlockResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDynamicBlock extends CreateRecord
{
    protected static string $resource = DynamicBlockResource::class;
}
