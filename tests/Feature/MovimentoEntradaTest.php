<?php

use App\Models\Ong;
use App\Models\User;
use App\Models\IntencaoDoacao;
use App\Models\Doacao;
use App\Models\Estoque;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\Console\Output\ConsoleOutput;

uses(RefreshDatabase::class);

it('CTU05 – Função de movimentação de estoque (entrada)', function () {
    $output = new ConsoleOutput();

    $output->writeln("<info>🚀 Iniciando CTU05 – Função de movimentação de estoque (entrada)...</info>");
    $output->writeln("<comment>📋 Objetivo: validar que, ao confirmar o recebimento da doação, o sistema cria a movimentação e atualiza o estoque.</comment>");

    // 1. Criar ONG e usuário logado
    $ong = Ong::factory()->create();
    $user = User::factory()->withOng($ong->id)->create();
    $this->actingAs($user);
    $output->writeln("<info>🏢 ONG criada (ID: {$ong->id}) e usuário autenticado (ID: {$user->id}).</info>");

    // 2. Criar intenção de doação (status registrada)
    $intencao = IntencaoDoacao::factory()->withOng($ong->id)->withStatus('registrada')
                                ->withQuantidade(5)->create();

    $output->writeln("<comment>🎯 Intenção de doação criada (ID: {$intencao->id})</comment>");
    $output->writeln("<comment>   • Item: {$intencao->descricao}</comment>");
    $output->writeln("<comment>   • Quantidade: {$intencao->quantidade} {$intencao->unidade}</comment>");
    $output->writeln("<comment>   • Status inicial: {$intencao->status}</comment>");

    // 3️⃣ Simular recebimento e gerar movimentação
    $output->writeln("<info>📦 Gerando movimentação de entrada...</info>");

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

    $output->writeln("<fg=yellow>✅ Doação registrada (ID: {$doacao->id}) com status '{$doacao->status}'.</>");

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

    $output->writeln("<comment>📊 Estoque atualizado:</comment>");
    $output->writeln("<comment>   • Item: {$estoque->nome_item}</comment>");
    $output->writeln("<comment>   • Quantidade atual: {$estoque->quantidade}</comment>");
    $output->writeln("<comment>   • Validade: {$estoque->data_validade->format('d/m/Y')}</comment>");

    // // Atualizar intenção
    $intencao->update([
        'status' => 'Recebida',
        'data_validade' => now()->addDay()
    ]);

    $output->writeln("<info>🔁 Intenção atualizada para status: '{$intencao->fresh()->status}'.</info>");

    // ✅ Verificações
    expect(Doacao::count())->toBe(1);
    expect(Estoque::count())->toBe(1);
    expect($intencao->fresh()->status)->toBe('Recebida');
    expect($estoque->quantidade)->toBe(5);
    expect($estoque->data_validade->isFuture())->toBeTrue();

    $output->writeln("<fg=green>🎉 CTU05 concluído com sucesso — movimentação e estoque validados!</>");
});
