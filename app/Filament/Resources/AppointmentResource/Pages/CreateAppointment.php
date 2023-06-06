<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use App\Filament\Resources\AppointmentResource;
use App\Models\Doctor;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateAppointment extends CreateRecord
{
    protected static string $resource = AppointmentResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        $appointment = $this->record;
        $doctor = Doctor::find($appointment->doctor_id);
        $user = User::find($doctor->user_id);
        return Notification::make()
            ->success()
            ->title('Appointment Created ')
            ->body('A new appointment has been affected to MR : ' . $doctor->last_name)
            ->send()
            ->sendToDatabase($user);
    }
}
