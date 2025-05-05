<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class UsersChart extends ChartWidget
{
    protected static ?string $heading = 'Users by Role';

    protected function getData(): array
    {
        $data = User::select('role', DB::raw('count(*) as count'))
            ->groupBy('role')
            ->pluck('count', 'role')
            ->toArray();
        return [
            'datasets' => [
                [
                    'label' => 'Users',
                    'data' => array_values($data),
                    'backgroundColor' => ['#FF6384', '#36A2EB', '#FFCE56'], // Example colors
                    'borderColor' => '#ffffff',
                ],
            ],
            'labels' => array_keys($data),
        ];
    }

    protected function getType(): string
    {
        return 'bubble';
    }
}
