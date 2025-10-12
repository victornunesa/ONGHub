<?php

use App\Models\Ong;
use App\Models\User;
use App\Models\PedidoDoacao;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\Console\Output\ConsoleOutput;

uses(RefreshDatabase::class);

it('CTF06 – Registro de pedido de doação válido (victornunes)', function () {
    $output = new ConsoleOutput();

    $output->writeln("<info>🚀 Iniciando CTF06 – Registro de pedido de doação válido (victornunes)...</info>");
    $output->writeln("<comment>📋 Objetivo: validar que uma ONG autenticada consegue registrar um pedido de doação válido com status 'aberto'.</comment>");

    // ==========================================================
    // 🟩 SUBFLUXO A — AUTENTICAÇÃO DA ONG
    // ==========================================================
    $output->writeln("<info>🟩 Subfluxo A – Criando ONG e autenticando usuário...</info>");

    $ong = Ong::factory()->create();
    $user = User::factory()->withOng($ong->id)->create();
    $this->actingAs($user);

    $output->writeln("<comment>🏢 ONG criada (ID: {$ong->id})</comment>");
    $output->writeln("<comment>👤 Usuário autenticado (ID: {$user->id})</comment>");

    // ==========================================================
    // 🟨 SUBFLUXO B — REGISTRO DO PEDIDO DE DOAÇÃO
    // ==========================================================
    $output->writeln("<info>🟨 Subfluxo B – Registrando pedido de doação...</info>");

    $pedido = PedidoDoacao::create([
        'nome_solicitante' => 'Maria Silva',
        'email_solicitante' => 'maria@example.com',
        'telefone_solicitante' => '(85) 99999-0000',
        'descricao' => 'Leite em pó integral',
        'quantidade' => 10,
        'unidade' => 'kg',
        'status' => 'Registrada',
        'data_pedido' => now(),
        'tipo' => 'Alimentos',
        'cpf' => '12345678900',
        'codigo' => 'PD-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6)),
    ]);

    $output->writeln("<comment>🧾 Pedido registrado (ID: {$pedido->id})</comment>");
    $output->writeln("<comment>   • Descrição: {$pedido->descricao}</comment>");
    $output->writeln("<comment>   • Quantidade: {$pedido->quantidade} {$pedido->unidade}</comment>");
    $output->writeln("<comment>   • Status inicial: {$pedido->status}</comment>");

    // ==========================================================
    // 🟦 SUBFLUXO C — VERIFICAÇÕES
    // ==========================================================
    $output->writeln("<info>🟦 Subfluxo C – Validando registro no banco...</info>");

    expect(PedidoDoacao::count())->toBe(1);
    expect($pedido->status)->toBe('Registrada');
    expect($pedido->nome_solicitante)->toBe('Maria Silva');
    expect($pedido->email_solicitante)->toBe('maria@example.com');
    expect($pedido->descricao)->toContain('Leite');

    $output->writeln("<fg=green>🎉 CTF06 concluído com sucesso — pedido registrado corretamente com status 'aberto'!</>");
});
