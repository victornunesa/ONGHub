<?php

use App\Models\Ong;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(Tests\TestCase::class, RefreshDatabase::class);

it('CTF04 – bloqueia login quando ONG está inativa', function () {
    // Pré-condição: ONG e usuário com status inativo
    $ong = Ong::create([
        'nome' => 'ONG Inativa',
        'cnpj' => '31.591.399/0001-56',
        'email' => 'onginativa@example.com',
        'telefone' => '11999999999',
        'endereco' => 'Rua Teste, 123',
        'status' => 'inativo', // ONG inativa
    ]);

    $password = 'Senha@123';

    $user = User::create([
        'name' => 'ONG Inativa',
        'email' => 'onginativa@example.com',
        'password' => Hash::make($password),
        'tipo' => 'ong',
        'status' => 'inativo', // Usuário inativo
        'ong_id' => $ong->id,
    ]);

    // Tenta logar com as credenciais corretas, mas conta inativa
    $response = $this->from('/login')->post(route('login'), [
        'email' => $user->email,
        'password' => $password,
    ]);

    // Verifica que houve redirecionamento de volta para a página de login
    $response->assertRedirect('/login');

    // Verifica se a mensagem de erro sobre conta inativa está na sessão
    $response->assertSessionHasErrors([
        'email' => 'Sua conta está inativa.',
    ]);

    // Garante que o usuário NÃO está autenticado
    $this->assertGuest();
});
