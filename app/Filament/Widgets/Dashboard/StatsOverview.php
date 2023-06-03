<?php

namespace App\Filament\Widgets\Dashboard;

use App\Models\medical_file;
use App\Models\patient;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class StatsOverview extends BaseWidget
{

    protected static ?int $sort = 0;

    protected function getCards(): array
    {
        $cards = [
            Card::make('Nombre total des patients', patient::count())
                ->icon('heroicon-o-users')
                ->description('le développement des patients dans le système')
                ->descriptionIcon('heroicon-o-trending-up')
                ->descriptionColor('success')
                ->color('success')
                ->chart([2, 10, 3, 12, 1, 14, 10, 1, 2, 10])
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),
            Card::make('Nombre total des Dossiers medicaux', medical_file::count())
                ->icon('heroicon-o-document')
                ->description('le développement des dossiers medicaux dans le système')
                ->descriptionIcon('heroicon-o-trending-up')
                ->descriptionColor('success')
                ->color('success')
                ->chart([2, 9, 8, 3, 7, 6, 10, 5, 6, 4])
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),
        ];
        $indexToRemove = array_search('Filament GitHub', array_column($cards, 'label'));
        if ($indexToRemove !== false) {
            unset($cards[$indexToRemove]);
        }

        return array_values($cards);
    }
}
