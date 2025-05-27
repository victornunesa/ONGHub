<?php

namespace App\Filament\Resources\EstoqueResource\Pages;

use App\Filament\Resources\EstoqueResource;
use COM;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Doacao;
use App\Models\IntencaoDoacao;
use Illuminate\Support\Facades\Auth;

class CreateEstoque extends CreateRecord
{
    protected static string $resource = EstoqueResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Estoque criado com sucesso';
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Salvar')
                ->submit('create'), // importante: chama o método de criação padrão do Filament
                // ->color('success') // opcional: muda a cor

            Action::make('cancel')
                ->label('Voltar')
                ->url($this->getResource()::getUrl('index'))
                ->color('gray'),
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['ong_id'] = Auth::user()->ong_id;
        $data['data_atualizacao'] = now();
        $data['quantidade_solicitada'] = 0;

        return $data;
    }

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        // Criar o estoque normalmente
        $estoque = parent::handleRecordCreation($data);

        // Criar o registro na tabela doacao
        Doacao::create([
            'intencao_id' => null,
            'pedido_id' => null,
            'ong_origem_id' => null,
            'ong_destino_id' => Auth::user()->ong_id,
            'nome_doador' => $data['nome_doador'],
            'email_doador' => $data['email_doador'] ?? null,
            'telefone_doador' => $data['telefone_doador'] ?? null,
            'descricao' =>  $data['nome_item'],
            'quantidade' => $data['quantidade'],
            'unidade' => $data['unidade'],
            'data_doacao' => now(),
            'status' => 'Entrada',
        ]);

        return $estoque;
    }
}
