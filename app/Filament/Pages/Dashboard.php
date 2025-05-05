<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\SubscriptionsChart;
use App\Filament\Widgets\UsersChart;
use Filament\Pages\Page;


class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.dashboard';

    public function getWidgets(): array
    {
        return [
            StatsOverview::class,
            UsersChart::class,
            SubscriptionsChart::class,
        ];
    }
}
