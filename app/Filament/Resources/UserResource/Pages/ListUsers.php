<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Components\Tab;
use App\Models\User;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()->where('role', 'enginer');
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Enginer')
                        ->badge(User::query()->where('role', 'enginer')->count()),
            'active' => Tab::make('Active Engineers')
                        ->modifyQueryUsing(fn (Builder $query) => $query->where('role', 'enginer')->where('status', 'active')) // Apply the condition for active enginers
                        ->badge(User::query()->where('role', 'enginer')->where('status', 'active')->count()),
                        
            'inactive' => Tab::make('Inactive Enginer')
                        ->modifyQueryUsing(fn (Builder $query) => $query->where('role', 'enginer')->where('status', 'inactive')) // Apply the condition for active enginers
                        ->badge(User::query()->where('role', 'enginer')->where('status', 'inactive')->count()),
        ];

    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
