<?php

namespace App\Filament\Resources\ConsultationResource\Pages;

use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Card;
use App\Filament\Resources\ConsultationResource;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Actions;
use Filament\Resources\Form;
use Filament\Resources\Pages\ViewRecord;
use Filament\Tables\Columns;
use Filament\Tables\Columns\TextColumn;

class ViewConsultation extends ViewRecord
{
    protected static string $resource = ConsultationResource::class;
    protected static ?string $title = 'Détails du Consultation';


    //protected static ?string $slug = '';

    //protected ?string $heading = 'kjj';





}
