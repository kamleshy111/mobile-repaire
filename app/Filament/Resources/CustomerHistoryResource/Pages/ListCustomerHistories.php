<?php

namespace App\Filament\Resources\CustomerHistoryResource\Pages;

use App\Filament\Resources\CustomerHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Components\Tab;
use App\Models\CustomerHistory;

class ListCustomerHistories extends ListRecords
{
    protected static string $resource = CustomerHistoryResource::class;

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
            ->badge(CustomerHistory::query()->count()),
            'This week' =>  Tab::make()
            ->modifyQueryUsing(fn (Builder $query) => $query->where('created_at', '>=', now()->subWeek()))
            ->badge(CustomerHistory::query()->where('created_at', '>=', now()->subWeek())->count()),
            'This Month' =>  Tab::make()
            ->modifyQueryUsing(fn (Builder $query) => $query->where('created_at', '>=', now()->subMonth()))
            ->badge(CustomerHistory::query()->where('created_at', '>=', now()->subMonth())->count()),
            'This Year' =>  Tab::make()
            ->modifyQueryUsing(fn (Builder $query) => $query->where('created_at', '>=', now()->subYear()))
            ->badge(CustomerHistory::query()->where('created_at', '>=', now()->subYear())->count()),
        ];

    }
}
