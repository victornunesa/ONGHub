<?php

use App\Models\Estoque;
use App\Models\Ong;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('CTU03 - Estoque Adicionar 5 itens', function () {
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

    $estoque->adicionarQuantidade(5);

    expect($estoque->fresh()->quantidade)->toBe(15);
});
