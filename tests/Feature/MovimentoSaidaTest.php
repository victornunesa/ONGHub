<?php

use App\Models\Ong;
use App\Models\User;
use App\Models\IntencaoDoacao;
use App\Models\Doacao;
use App\Models\Estoque;
use App\Models\PedidoDoacao;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\Console\Output\ConsoleOutput;

uses(RefreshDatabase::class);

it('CTU06 – Função de movimentação de estoque (saída)', function () {
    $output = new ConsoleOutput();

    $output->writeln("<info>🚀 Iniciando CTU06 – Função de movimentação de estoque (saída)...</info>");
    $output->writeln("<comment>📋 Objetivo: validar a retirada de itens do estoque e registro da movimentação de saída.</comment>");

    // 1. Criar ONG e usuário logado
    $ong = Ong::factory()->create();
    $user = User::factory()->withOng($ong->id)->create();
    $estoqueItemRandom = Estoque::factory()->withOng($ong->id)->withQuantidade(10)->create();
    $this->actingAs($user);

    $output->writeln("<info>🏢 ONG criada (ID: {$ong->id}) e usuário autenticado (ID: {$user->id}).</info>");
    $output->writeln("<comment>📦 Item inicial em estoque:</comment>");
    $output->writeln("<comment>   • Nome: {$estoqueItemRandom->nome_item}</comment>");
    $output->writeln("<comment>   • Quantidade inicial: {$estoqueItemRandom->quantidade}</comment>");
    $output->writeln("<comment>   • Unidade: {$estoqueItemRandom->unidade}</comment>");

    // 2. pedido
    $pedidoDoacao = PedidoDoacao::factory()->withQuantidade(5)->create();

    $output->writeln("<info>🧾 Pedido de doação criado (ID: {$pedidoDoacao->id})</info>");
    $output->writeln("<comment>   • Item solicitado: {$pedidoDoacao->descricao}</comment>");
    $output->writeln("<comment>   • Quantidade solicitada: {$pedidoDoacao->quantidade}</comment>");

    $output->writeln("<info>🔁 Processando movimentação de saída...</info>");
    $estoqueItemRandom->quantidade -= $pedidoDoacao->quantidade;
    $estoqueItemRandom->save();

    $output->writeln("<comment>📊 Estoque atualizado:</comment>");
    $output->writeln("<comment>   • Quantidade final: {$estoqueItemRandom->quantidade}</comment>");

    $doacao = Doacao::create([
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

    $output->writeln("<fg=yellow>✅ Movimentação registrada na tabela Doação (ID: {$doacao->id}, Status: {$doacao->status}).</>");

    // // Atualiza status do pedido
    $output->writeln("<info>🧩 Atualizando status do pedido...</info>");
    if ($pedidoDoacao->quantidade_necessaria == 0) {
        $pedidoDoacao->update(['status' => 'Doação completa']);
    } else {
        $pedidoDoacao->update(['status' => 'Doação em parte']);
    }

    $output->writeln("<comment>📦 Status final do pedido: {$pedidoDoacao->fresh()->status}</comment>");

    expect($estoqueItemRandom->fresh()->quantidade)->toBe(5);
    expect(Doacao::count())->toBe(1);
    expect(Doacao::first()->status)->toBe('Saida');
    expect($pedidoDoacao->fresh()->status)->toBe('Doação completa');

    $output->writeln("<fg=green>🎉 CTU06 concluído com sucesso — movimentação de saída validada!</>");

});
