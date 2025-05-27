<?php

namespace App\Filament\Resources\IntencaoDoacaoResource\Pages;

use App\Filament\Resources\IntencaoDoacaoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIntencaoDoacaos extends ListRecords
{
    protected static string $resource = IntencaoDoacaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //Actions\CreateAction::make(),
        ];
    }
}
