<?php

namespace App\Filament\Resources\MedicalFileResource\Pages;

use App\Filament\Resources\MedicalFileResource;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Actions;
use Filament\Resources\Form;
use Filament\Resources\Pages\ViewRecord;

class ViewMedicalFile extends ViewRecord
{
    protected static string $resource = MedicalFileResource::class;
    protected static ?string $title = 'Détails du dossier médical';

    //    protected static string $relationship = 'appointment';


    //    public  function form(Form $form): Form
    //    {
    //        // Customize the form fields and behavior here
    //        $form
    //            ->schema([
    //                TextInput::make('first_name')->label('Prénom'),
    //                TextInput::make('last_name')->label('Nom'),
    //                TextInput::make('cin')->label('CIN'),
    //                TextInput::make('num')->label('Tel'),
    //                TextInput::make('email')->label('Email'),
    //            ]);
    //        return $form;
    //    }

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
