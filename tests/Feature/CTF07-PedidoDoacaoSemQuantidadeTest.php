<?php

use App\Models\Ong;
use App\Models\PedidoDoacao;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Console\Output\ConsoleOutput;

uses(RefreshDatabase::class);

it('CTF07 – Registro de pedido de doação inválido (dados obrigatórios ausentes)', function () {
    $output = new ConsoleOutput();

    $output->writeln("<info>🚀 Iniciando CTF07 – Registro de pedido de doação inválido (dados obrigatórios ausentes)...</info>");
    $output->writeln("<comment>📋 Objetivo: garantir que o sistema bloqueia o registro de um pedido sem preencher a quantidade.</comment>");

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
    // 🟥 SUBFLUXO B — TENTATIVA DE REGISTRO INVÁLIDO
    // ==========================================================
    $output->writeln("<info>🟥 Subfluxo B – Tentando registrar pedido sem quantidade...</info>");

    $data = [
        'nome_solicitante' => 'João Pereira',
        'email_solicitante' => 'joao@example.com',
        'telefone_solicitante' => '(85) 98888-7777',
        'descricao' => 'Arroz integral',
        // ❌ Campo 'quantidade' ausente propositalmente
        'unidade' => 'kg',
        'status' => 'aberto',
        'data_pedido' => now(),
        'tipo' => 'Alimentos',
        'cpf' => '12345678900',
        'codigo' => 'PD-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6)),
    ];

    // ==========================================================
    // 🧠 EXECUTANDO VALIDAÇÃO
    // ==========================================================
    // Validação com as rules do model
    $validator = Validator::make($data, PedidoDoacao::rules());

    // ==========================================================
    // 🧾 RESULTADO DA VALIDAÇÃO
    // ==========================================================
    if ($validator->fails()) {
        $output->writeln("<fg=red>❌ Validação falhou conforme esperado!</>");
        $output->writeln("<fg=yellow>🧾 Erros de validação detectados:</>");
        foreach ($validator->errors()->all() as $error) {
            $output->writeln("<fg=red>  - {$error}</>");
        }
    } else {
        $output->writeln("<fg=green>✅ Nenhum erro encontrado (ERRO: deveria falhar!)</>");
    }

    // ==========================================================
    // 🟦 SUBFLUXO C — ASSERTIVAS DE TESTE
    // ==========================================================
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('quantidade'))->toBeTrue();

    $output->writeln("<fg=green>🎉 CTF07 concluído com sucesso — sistema bloqueou corretamente o pedido sem quantidade!</>");
});
