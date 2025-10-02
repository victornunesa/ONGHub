<?php

use App\Models\Ong;
use App\Models\User;
use App\Models\IntencaoDoacao;
use App\Models\Doacao;
use App\Models\Estoque;
use App\Models\PedidoDoacao;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('recebe um pedido de doação e doa a quantidade pedida', function () {
    // 1. Criar ONG e usuário logado
    $ong = Ong::factory()->create();
    $user = User::factory()->withOng($ong->id)->create();
    $estoqueItemRandom = Estoque::factory()->withOng($ong->id)->withQuantidade(10)->create();
    $this->actingAs($user);

    // 2. pedido
    $pedidoDoacao = PedidoDoacao::factory()->withQuantidade(5)->create();

    $estoqueItemRandom->quantidade -= $pedidoDoacao->quantidade;
    $estoqueItemRandom->save();

    Doacao::create([
        'pedido_id' => $pedidoDoacao->id,
        'nome_doador' => $pedidoDoacao->nome_solicitante,
        'email_doador' => $pedidoDoacao->email_solicitante,
        'telefone_doador' => $pedidoDoacao->telefone_solicitante,
        'descricao' => $pedidoDoacao->descricao,
        'quantidade' => $pedidoDoacao->quantidade,
        'unidade' => $estoqueItemRandom->unidade,
        'data_doacao' => now(),
        'status' => 'Saida',
        'ong_destino_id' =>  null,
        'ong_origem_id' =>  auth()->user()->ong->id
    ]);

    // // Atualiza status do pedido
    if ($pedidoDoacao->quantidade_necessaria == 0) {
        $pedidoDoacao->update(['status' => 'Doação completa']);
    } else {
        $pedidoDoacao->update(['status' => 'Doação em parte']);
    }

    expect($estoqueItemRandom->fresh()->quantidade)->toBe(5);
    expect(Doacao::count())->toBe(1);
    expect(Doacao::first()->status)->toBe('Saida');
    expect($pedidoDoacao->fresh()->status)->toBe('Doação completa');

});
