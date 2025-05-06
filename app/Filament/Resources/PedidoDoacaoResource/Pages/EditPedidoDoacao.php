<?php

namespace App\Filament\Resources\PedidoDoacaoResource\Pages;

use App\Filament\Resources\PedidoDoacaoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPedidoDoacao extends EditRecord
{
    protected static string $resource = PedidoDoacaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
