<?php

use App\Models\Ong;
use App\Models\User;
use App\Models\IntencaoDoacao;
use App\Models\Doacao;
use App\Models\Estoque;
use App\Models\PedidoDoacao;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\Console\Output\ConsoleOutput;

uses(RefreshDatabase::class);

it('CTF05 – Fluxo completo da doação (entrada + saída)', function () {
    $output = new ConsoleOutput();

    $output->writeln("<info>🚀 Iniciando CTF05 – Fluxo completo da doação (entrada + saída)...</info>");
    $output->writeln("<comment>📋 Objetivo: validar o ciclo completo da ONG — recebendo doações e realizando saídas de estoque para pedidos.</comment>");

    // ==========================================================
    // 🟩 SUBFLUXO A — REGISTRAR INTENÇÃO DE DOAÇÃO (ENTRADA)
    // ==========================================================
    $output->writeln("<info>🟩 Subfluxo A – Registrando intenção de doação...</info>");

    $ong = Ong::factory()->create();
    $user = User::factory()->withOng($ong->id)->create();
    $this->actingAs($user);

    $intencao = IntencaoDoacao::factory()
        ->withOng($ong->id)
        ->withStatus('registrada')
        ->withQuantidade(10)
        ->create();

    $output->writeln("<comment>🎯 Intenção registrada (ID: {$intencao->id})</comment>");
    $output->writeln("<comment>   • Item: {$intencao->descricao}</comment>");
    $output->writeln("<comment>   • Quantidade: {$intencao->quantidade} {$intencao->unidade}</comment>");

    expect($intencao->status)->toBe('registrada');

    // ==========================================================
    // 🟨 SUBFLUXO B — CONFIRMAR RECEBIMENTO E ATUALIZAR ESTOQUE
    // ==========================================================
    $output->writeln("<info>🟨 Subfluxo B – Confirmando recebimento e atualizando estoque...</info>");

    $doacaoEntrada = Doacao::create([
        'intencao_id' => $intencao->id,
        'nome_doador' => $intencao->nome_solicitante,
        'email_doador' => $intencao->email_solicitante,
        'telefone_doador' => $intencao->telefone_solicitante,
        'descricao' => $intencao->descricao,
        'quantidade' => $intencao->quantidade,
        'unidade' => $intencao->unidade,
        'data_doacao' => now(),
        'data_validade' => now()->addDays(15),
        'status' => 'Entrada',
        'ong_destino_id' => $ong->id,
        'ong_origem_id' => null,
    ]);

    $estoque = Estoque::updateOrCreate(
        [
            'ong_id' => $ong->id,
            'nome_item' => $intencao->descricao,
            'unidade' => $intencao->unidade,
        ],
        [
            'quantidade' => 0,
            'data_atualizacao' => now(),
            'data_validade' => now()->addDays(15),
        ]
    );
    $estoque->increment('quantidade', $intencao->quantidade);

    $intencao->update(['status' => 'Recebida']);

    $output->writeln("<fg=yellow>✅ Doação de entrada registrada e estoque atualizado.</>");
    $output->writeln("<comment>   • Quantidade em estoque: {$estoque->quantidade}</comment>");

    // ==========================================================
    // 🟦 SUBFLUXO C — CRIAR PEDIDO DE DOAÇÃO (SAÍDA)
    // ==========================================================
    $output->writeln("<info>🟦 Subfluxo C – Criando pedido de doação...</info>");

    $pedido = PedidoDoacao::factory()->withQuantidade(4)->create();

    $output->writeln("<comment>🧾 Pedido criado (ID: {$pedido->id})</comment>");
    $output->writeln("<comment>   • Item solicitado: {$pedido->descricao}</comment>");
    $output->writeln("<comment>   • Quantidade solicitada: {$pedido->quantidade}</comment>");

    // ==========================================================
    // 🟥 SUBFLUXO D — ATENDER PEDIDO (GERAR MOVIMENTAÇÃO DE SAÍDA)
    // ==========================================================
    $output->writeln("<info>🟥 Subfluxo D – Atendendo pedido e gerando saída de estoque...</info>");

    $estoque->decrement('quantidade', $pedido->quantidade);

    $doacaoSaida = Doacao::create([
        'pedido_id' => $pedido->id,
        'nome_doador' => $pedido->nome_solicitante,
        'email_doador' => $pedido->email_solicitante,
        'telefone_doador' => $pedido->telefone_solicitante,
        'descricao' => $pedido->descricao,
        'quantidade' => $pedido->quantidade,
        'unidade' => $estoque->unidade,
        'data_doacao' => now(),
        'status' => 'Saida',
        'ong_destino_id' => null,
        'ong_origem_id' => $ong->id,
    ]);

    $pedido->update(['status' => 'Doação completa']);

    $output->writeln("<fg=yellow>✅ Movimentação de saída registrada (Doação ID: {$doacaoSaida->id}).</>");
    $output->writeln("<comment>   • Quantidade restante no estoque: {$estoque->fresh()->quantidade}</comment>");
    $output->writeln("<comment>   • Status do pedido: {$pedido->fresh()->status}</comment>");

    // ==========================================================
    // 🟧 SUBFLUXO E — VALIDAÇÕES FINAIS
    // ==========================================================
    $output->writeln("<info>🟧 Subfluxo E – Validando estado final do sistema...</info>");

    expect(Doacao::count())->toBe(2); // entrada e saída
    expect(Estoque::count())->toBe(1);
    expect($intencao->fresh()->status)->toBe('Recebida');
    expect($estoque->fresh()->quantidade)->toBe(6); // 10 recebidos - 4 doados
    expect($pedido->fresh()->status)->toBe('Doação completa');
    expect(Doacao::where('status', 'Entrada')->count())->toBe(1);
    expect(Doacao::where('status', 'Saida')->count())->toBe(1);

    $output->writeln("<fg=green>🎉 CTF05 concluído com sucesso — fluxo completo de entrada e saída validado!</>");
});
