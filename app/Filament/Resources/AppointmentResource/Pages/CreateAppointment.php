<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use App\Filament\Resources\AppointmentResource;
use App\Models\Doctor;
use App\Models\User;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Wizard\Step;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;

class CreateAppointment extends CreateRecord
{
    protected static string $resource = AppointmentResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function afterCreate(): void
    {
        $appointment = $this->record;
        $doctor = Doctor::find($appointment->doctor_id);
        $user = User::find($doctor->user_id);

        Notification::make()
            ->title('Nouveau rendez-vous')
            ->icon('heroicon-o-calendar')
            // ->body("**{$appointment->customer->name} ordered {$order->items->count()} products.**")
            ->body('Un nouveau rendez-vous est affecte par Mr : ' . auth()->user()->name)
            ->actions([
                Action::make('Découvrir')
                    ->url(AppointmentResource::getUrl('edit', ['record' => $appointment])),
            ])
            ->sendToDatabase($user);
    }

    // protected function getCreatedNotification(): ?Notification
    // {
    //     $appointment = $this->record;
    //     $doctor = Doctor::find($appointment->doctor_id);
    //     $user = User::find($doctor->user_id);
    //     return Notification::make()
    //         ->success()
    //         ->title('Appointment Created ')
    //         ->body('A new appointment has been affected to MR : ' . $doctor->last_name)
    //         ->send()
    //         ->sendToDatabase($user);
    // }
    //protected static ?string $activeNavigationIcon =
    //protected static ?string $title = 'Détails du Rendez-vous';

}
