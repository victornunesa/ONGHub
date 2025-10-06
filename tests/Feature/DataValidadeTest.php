<?php

use App\Models\Estoque;
use App\Models\Ong;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Console\Output\ConsoleOutput;

uses(RefreshDatabase::class);

it('CTU04 – Validação de data de validade', function () {
    $output = new ConsoleOutput();

    $output->writeln("<info>🚀 Iniciando CTU04 – Validação de data de validade...</info>");
    $output->writeln("<comment>📋 Cenário: Tentar cadastrar item com data de validade anterior à atual.</comment>");

    // Pré-condição: nenhuma
    $ong = Ong::factory()->create();
    $output->writeln("<info>✅ ONG criada com sucesso (ID: {$ong->id}).</info>");

    $data = [
        'ong_id' => $ong->id,
        'nome_item' => 'Arroz',
        'quantidade' => 10,
        'unidade' => 'kg',
        'data_validade' => now()->subDay(), // ontem (inválido)
    ];

    $output->writeln("<comment>🧪 Testando item: {$data['nome_item']} ({$data['quantidade']} {$data['unidade']})</comment>");
    $output->writeln("<comment>📅 Data de validade informada: {$data['data_validade']->format('d/m/Y')}</comment>");

    // Validação com as rules do model
    $validator = Validator::make($data, Estoque::rules());

    if ($validator->fails()) {
        $output->writeln("<fg=red>❌ Validação falhou conforme esperado!</>");
        $output->writeln("<fg=yellow>🧾 Erros de validação:</>");
        foreach ($validator->errors()->all() as $error) {
            $output->writeln("<fg=red>  - {$error}</>");
        }
    } else {
        $output->writeln("<fg=green>✅ Nenhum erro encontrado (ERRO: deveria falhar)</>");
    }

    // ✅ Resultado esperado: validação falha
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('data_validade'))->toBeTrue();

    $output->writeln("<info>🏁 CTU04 concluído com sucesso.</info>");
});
