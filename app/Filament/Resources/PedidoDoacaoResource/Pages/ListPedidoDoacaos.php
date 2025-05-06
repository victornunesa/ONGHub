<?php

namespace App\Filament\Resources\PedidoDoacaoResource\Pages;

use App\Filament\Resources\PedidoDoacaoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPedidoDoacaos extends ListRecords
{
    protected static string $resource = PedidoDoacaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
