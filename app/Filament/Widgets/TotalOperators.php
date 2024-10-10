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

            Stat::make('Total Engineries', User::Where('role','=', 'enginer')->count())->description('Total Engineries'),

            Stat::make('Active Engineries', User::Where([['role','=', 'enginer'],['status','=', 'active']])->count())->description('Active Engineries'),

            Stat::make('Completed Repairs', Repair::Where('status','=', 'completed')->count())->description('All Completed Repairs'),

            Stat::make('In Progress Repairs', Repair::Where('status','=', 'in_progress')->count())->description('Repairs in Progress'),

            Stat::make('Pending Repairs', Repair::Where('status','=', 'pending')->count())->description('Pending Repairs'),
            
            Stat::make('Cance Repairs', Repair::Where('status','=', 'cance')->count())->description('Canceled Repairs'),

        ];
    }
}
