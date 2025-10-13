<?php

use App\Models\Ong;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

it('impede o cadastro de ONG e User com CNPJ duplicado', function () {
    $cnpjDuplicado = '12.345.678/0001-90';

    // Pré-condição: já existe uma ONG com esse CNPJ
    Ong::create([
        'nome' => 'ONG Existente',
        'cnpj' => $cnpjDuplicado,
        'email' => 'existente@example.com',
        'telefone' => '11999999999',
        'endereco' => 'Rua Existente, 123',
        'status' => 'ativo',
    ]);

    // Tenta cadastrar nova ONG com mesmo CNPJ
    $response = $this->post(route('ong.register'), [
        'nome' => 'Nova ONG',
        'cnpj' => $cnpjDuplicado,
        'email' => 'nova@example.com',
        'telefone' => '11888888888',
        'endereco' => 'Rua Nova, 456',
        'password' => 'Senha@123',
        'password_confirmation' => 'Senha@123',
    ]);

    //Validação deve falhar no campo 'cnpj'
    $response->assertSessionHasErrors('cnpj');

    //ONG não deve ser criada novamente com o mesmo CNPJ
    expect(Ong::where('cnpj', $cnpjDuplicado)->count())->toBe(1);

    //Usuário não deve ser criado com o novo e-mail
    expect(User::where('email', 'nova@example.com')->exists())->toBeFalse();
});







