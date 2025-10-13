<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use function Pest\Laravel\post;
use function Pest\Laravel\assertAuthenticated;
use function Pest\Laravel\assertDatabaseHas;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

it('CTF01 – cadastra uma ONG com CNPJ ativo', function () {
    Http::fake([
        'https://brasilapi.com.br/api/cnpj/v1/*' => Http::response([
            'cnpj' => '33050071000158',
            'razao_social' => 'ONG Teste',
            'descricao_situacao_cadastral' => 'ATIVA',
        ], 200),
    ]);

    $data = [
        'nome' => 'ONG Solidária',
        'cnpj' => '33050071000158',
        'email' => 'contato@ongsolidaria.org',
        'telefone' => '(11) 99999-8888',
        'endereco' => 'Rua das Flores, 123',
        'password' => 'Senha@123',
        'password_confirmation' => 'Senha@123',
    ];

    $response = post(route('ong.register'), $data);

    $response->assertRedirect('/admin');

    assertDatabaseHas('ong', [
        'nome' => 'ONG Solidária',
        'cnpj' => '33050071000158',
        'email' => 'contato@ongsolidaria.org',
        'status' => 'ativo',
    ]);

    assertDatabaseHas('users', [
        'email' => 'contato@ongsolidaria.org',
        'tipo' => 'ong',
        'status' => 'ativo',
    ]);

    assertAuthenticated();
});
