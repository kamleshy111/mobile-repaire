<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\Repair;

class TotalOperators extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Operators', User::Where('role','=', 'Operator')->count())->description('New Total Operators'),
            Stat::make('Completed Repairs', Repair::Where('status','=', 'Completed')->count())->description('All Completed Divece Repairs'),
            Stat::make('In Progress Repairs', Repair::Where('status','=', 'In Progress')->count()),
            Stat::make('Pending Repairs', Repair::Where('status','=', 'Pending')->count()),
            Stat::make('Cance Repairs', Repair::Where('status','=', 'Cance')->count()),
        ];
    }
}
