<?php

namespace App\Filament\Resources\OngResource\Pages;

use App\Filament\Resources\OngResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOng extends EditRecord
{
    protected static string $resource = OngResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
