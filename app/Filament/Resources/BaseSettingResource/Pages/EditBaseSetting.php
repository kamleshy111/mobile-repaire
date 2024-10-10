<?php

namespace App\Filament\Resources\BaseSettingResource\Pages;

use App\Filament\Resources\BaseSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBaseSetting extends EditRecord
{
    protected static string $resource = BaseSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
