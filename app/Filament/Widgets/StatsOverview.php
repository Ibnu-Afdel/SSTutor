<?php

namespace App\Filament\Widgets;

use App\Models\InstructorApplication;
use App\Models\Subscription;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Pending Manual Subscriptions', Subscription::where('payment_method', 'manual')->where('status', 'pending')->count())
                ->description('Manual payments awaiting approval')
                ->color('warning'),
            Stat::make('Pending Instructor Applications', InstructorApplication::where('status', 'pending')->count())
                ->description('Applications awaiting review')
                ->color('warning'),
            Stat::make('Total Pro Users', User::where('is_pro', true)->whereDate('pro_expires_at', '>', now())->count())
                ->description('Users with active pro status')
                ->color('success'),
            Stat::make('Total Users', User::count())
                ->description('All registered users')
                ->color('primary'),
        ];
    }
}
