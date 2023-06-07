<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Illuminate\Support\Carbon;

class UserStats extends BaseWidget
{
    protected function getCards(): array
    {

        $oneMonthAgo = Carbon::now()->subMonth();

        $currentMonthStart = Carbon::now()->startOfMonth();
        $previousMonthStart = Carbon::now()->subMonth()->startOfMonth();

        $currentMonthNewUsers = User::where('created_at', '>=', $currentMonthStart)->count();
        $previousMonthNewUsers = User::whereBetween('created_at', [$previousMonthStart, $currentMonthStart])->count();

        $isIncrease = false; // Initialize the variable as false

        $percentageIncrease = ($currentMonthNewUsers - $previousMonthNewUsers) / $previousMonthNewUsers * 100;

        $percentageIncrease = intval($percentageIncrease);
        if ($percentageIncrease > 0) {
            $isIncrease = true; // Set to true if there is an increase
        }

        return [
            Card::make('Total Utilisateurs', User::count()),
            Card::make('Nouveaux Utilisateurs', User::where('created_at', '>', $oneMonthAgo)->count())
                ->color($isIncrease ? 'success' : 'danger')
                ->chart([2, 10, 3, 12, 1, 14, 10, 1, 2, 10])
                ->description($isIncrease ? 'Une augmentation de ' . $percentageIncrease . '%' : 'Une diminution de ' . abs($percentageIncrease) . '%')
                ->descriptionIcon($isIncrease ? 'heroicon-s-trending-up' : 'heroicon-s-trending-down'),


            // Card::make('Rendez-vous annulés', appointment::where('status', 'annulé')->count())
            //     ->color('danger')
            //     ->chart([2, 10, 3, 12, 1, 14, 10, 1, 2, 10])
        ];
    }
}
