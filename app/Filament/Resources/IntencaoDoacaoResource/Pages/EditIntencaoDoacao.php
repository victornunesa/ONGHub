<?php

namespace App\Filament\Resources\IntencaoDoacaoResource\Pages;

use App\Filament\Resources\IntencaoDoacaoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditIntencaoDoacao extends EditRecord
{
    protected static string $resource = IntencaoDoacaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
