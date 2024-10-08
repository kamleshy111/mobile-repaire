<?php

namespace App\Filament\Resources\RepairResource\Pages;

use App\Filament\Resources\RepairResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Components\Tab;
use App\Models\Repair;

class ListRepairs extends ListRecords
{
    protected static string $resource = RepairResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make()
            ->badge(Repair::query()->count()),
            'This week' =>  Tab::make()
            ->modifyQueryUsing(fn (Builder $query) => $query->where('created_at', '>=', now()->subWeek()))
            ->badge(Repair::query()->where('created_at', '>=', now()->subWeek())->count()),
            'This Month' =>  Tab::make()
            ->modifyQueryUsing(fn (Builder $query) => $query->where('created_at', '>=', now()->subMonth()))
            ->badge(Repair::query()->where('created_at', '>=', now()->subMonth())->count()),
            'This Year' =>  Tab::make()
            ->modifyQueryUsing(fn (Builder $query) => $query->where('created_at', '>=', now()->subYear()))
            ->badge(Repair::query()->where('created_at', '>=', now()->subYear())->count()),
        ];

    }
}
