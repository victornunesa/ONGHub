<?php

namespace App\Filament\Resources\MovimentacaoDoacaoResource\Pages;

use App\Filament\Resources\MovimentacaoDoacaoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMovimentacaoDoacaos extends ListRecords
{
    protected static string $resource = MovimentacaoDoacaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(), // Remova se não quiser criar registros
        ];
    }
}