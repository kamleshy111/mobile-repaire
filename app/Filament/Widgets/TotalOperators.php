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
        $totalEstimatedCost = Repair::sum('estimated_cost');
        $totalFinalCost = Repair::sum('final_cost');
        $differentCost = $totalEstimatedCost - $totalFinalCost;

        return [

            Stat::make('Repairs Estimated Cost', number_format($totalEstimatedCost, 2))
                ->description('Repairs total estimated cost'),

            Stat::make('Repairs Final Cost', number_format($totalFinalCost, 2))
                ->description('Repairs total final cost'),

            Stat::make('Cost Difference', number_format($differentCost, 2))
                ->description('Difference between estimated and final cost'),

            Stat::make('Total Operators', User::Where('role','=', 'Operator')->count())->description('Total Operators'),

            Stat::make('Active Operators', User::Where([['role','=', 'Operator'],['status','=', 'Active']])->count())->description('Active Operators'),

            Stat::make('Completed Repairs', Repair::Where('status','=', 'Completed')->count())->description('All Completed Repairs'),

            Stat::make('In Progress Repairs', Repair::Where('status','=', 'In Progress')->count())->description('Repairs in Progress'),

            Stat::make('Pending Repairs', Repair::Where('status','=', 'Pending')->count())->description('Pending Repairs'),
            
            Stat::make('Cance Repairs', Repair::Where('status','=', 'Cance')->count())->description('Canceled Repairs'),

        ];
    }
}
