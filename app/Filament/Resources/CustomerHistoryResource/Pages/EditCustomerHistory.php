<?php

namespace App\Filament\Resources\CustomerHistoryResource\Pages;

use App\Filament\Resources\CustomerHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCustomerHistory extends EditRecord
{
    protected static string $resource = CustomerHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
