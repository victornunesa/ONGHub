<?php

use App\Models\Ong;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(Tests\TestCase::class, RefreshDatabase::class);

it('permite login com credenciais válidas', function () {
    // Pré-condição: criar ONG e usuário ativo
    $ong = Ong::create([
        'nome' => 'ONG Teste',
        'cnpj' => '31.591.399/0001-56',
        'email' => 'ongteste@example.com',
        'telefone' => '11999999999',
        'endereco' => 'Rua Teste, 123',
        'status' => 'ativo',
    ]);

    $password = 'Senha@123';

    $user = User::create([
        'name' => 'ONG Teste',
        'email' => 'ongteste@example.com',
        'password' => Hash::make($password),
        'tipo' => 'ong',
        'status' => 'ativo',
        'ong_id' => $ong->id,
    ]);

    // Faz o POST para a rota login com as credenciais válidas
    $response = $this->post(route('login'), [
        'email' => $user->email,
        'password' => $password,
    ]);

    // Verifica se foi redirecionado para /admin
    $response->assertRedirect('/admin');

    // Confirma que o usuário está autenticado
    $this->assertAuthenticatedAs($user);
});
