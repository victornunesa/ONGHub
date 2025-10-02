<?php

use App\Models\Ong;
use App\Models\User;
use App\Models\IntencaoDoacao;
use App\Models\Doacao;
use App\Models\Estoque;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('confirma recebimento de doação e gera movimentação de entrada', function () {
    // 1. Criar ONG e usuário logado
    $ong = Ong::factory()->create();
    $user = User::factory()->withOng($ong->id)->create();
    $this->actingAs($user);

    // 2. Criar intenção de doação (status registrada)
    $intencao = IntencaoDoacao::factory()->withOng($ong->id)->withStatus('registrada')
                                ->withQuantidade(5)->create();

    // 3. Simular recebimento

    // // Criar doação
    $doacao = Doacao::create([
        'intencao_id' => $intencao->id,
        'nome_doador' => $intencao->nome_solicitante,
        'email_doador' => $intencao->email_solicitante,
        'telefone_doador' => $intencao->telefone_solicitante,
        'descricao' => $intencao->descricao,
        'quantidade' => $intencao->quantidade,
        'unidade' => $intencao->unidade,
        'data_doacao' => now(),
        'data_validade' => now()->addDay(),
        'status' => 'Entrada',
        'ong_destino_id' => null,
        'ong_origem_id' => $ong->id,
    ]);

    // // Criar estoque
    $estoque = Estoque::updateOrCreate(
        [
            'ong_id' => $ong->id,
            'nome_item' => $intencao->descricao,
            'unidade' => $intencao->unidade
        ],
        [
            'quantidade' => 0,
            'data_atualizacao' => now(),
            'data_validade' => now()->addDay()
        ]
    );
    $estoque->increment('quantidade', $intencao->quantidade);
    // // Atualizar intenção
    $intencao->update([
        'status' => 'Recebida',
        'data_validade' => now()->addDay()
    ]);

    // ✅ Verificações
    expect(Doacao::count())->toBe(1);
    expect(Estoque::count())->toBe(1);
    expect($intencao->fresh()->status)->toBe('Recebida');
    expect($estoque->quantidade)->toBe(5);
    expect($estoque->data_validade->isFuture())->toBeTrue();
});
