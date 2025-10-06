<?php

use App\Models\Estoque;
use App\Models\Ong;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\Console\Output\ConsoleOutput;

uses(RefreshDatabase::class);

it('CTU03 - Estoque Adicionar 5 itens', function () {
    $output = new ConsoleOutput();
    $output->writeln("<info>🚀 Iniciando teste de adição de itens no estoque...</info>");

    $ong = Ong::factory()->create();
    // Pré-condição: estoque inicial = 10
    $estoque = Estoque::create([
        'ong_id' => $ong->id,
        'nome_item' => 'Arroz',
        'quantidade' => 10,
        'unidade' => 'kg',
        'data_atualizacao' => now(),
        'data_validade' => now()->addMonth()
    ]);

    $output->writeln("<comment>📦 Estoque inicial: {$estoque->quantidade}</comment>");

    $estoque->adicionarQuantidade(5);

    $output->writeln("<question>✅ Estoque final: {$estoque->fresh()->quantidade}</question>");

    dump([
        'Estoque inicial' => 10,
        'Adicionado' => 5,
        'Estoque final' => $estoque->fresh()->quantidade,
    ]);

    expect($estoque->fresh()->quantidade)->toBe(15);
});
