<?php
// tests/Unit/CTU01EmailValidationTest.php

use App\Validators\EmailValidator;

// CTU01 – Validação de e-mail
test('CTU01 - email válido "teste@teste.com" retorna true', function () {
    // Pré-condição: Nenhuma
    // Entrada: "teste@teste.com"
    // Resultado esperado: verdadeiro
    
    $resultado = EmailValidator::validate('teste@teste.com');
    expect($resultado)->toBeTrue();
});

test('CTU01 - email inválido "teste@" retorna false', function () {
    // Pré-condição: Nenhuma  
    // Entrada: "teste@"
    // Resultado esperado: falso
    
    $resultado = EmailValidator::validate('teste@');
    expect($resultado)->toBeFalse();
});

// Testes parametrizados para mais cenários
test('valida múltiplos formatos de email', function (string $email, bool $esperado) {
    expect(EmailValidator::validate($email))->toBe($esperado);
})->with([
    ['teste@teste.com', true],
    ['teste@', false],
    ['usuario@exemplo.com.br', true],
    ['email-invalido', false],
    ['', false],
    ['nome.sobrenome@empresa.com', true],
    ['teste@dominio.', false],
    ['a@b.co', true],
    ['teste@dominio..com', false],
]);