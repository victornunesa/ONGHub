<?php

use App\Models\Estoque;
use App\Models\Ong;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;

uses(RefreshDatabase::class);

it('CTU04 - bloqueia cadastro de item com data de validade anterior a hoje', function () {
    // Pré-condição: nenhuma
    $ong = Ong::factory()->create();

    $data = [
        'ong_id' => $ong->id,
        'nome_item' => 'Arroz',
        'quantidade' => 10,
        'unidade' => 'kg',
        'data_validade' => now()->subDay(), // ontem (inválido)
    ];

    // Validação com as rules do model
    $validator = Validator::make($data, Estoque::rules());

    // ✅ Resultado esperado: validação falha
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('data_validade'))->toBeTrue();
});
