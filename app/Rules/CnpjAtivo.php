<?php

namespace App\Rules;

use App\Services\BrasilApiService;
use Illuminate\Contracts\Validation\Rule;

class CnpjAtivo implements Rule
{
    protected $brasilApiService;
    protected $mensagem;

    public function __construct()
    {
        $this->brasilApiService = new BrasilApiService();
    }

    public function passes($attribute, $value)
    {
        $cnpj = preg_replace('/[^0-9]/', '', $value);

        // Primeiro valida o formato localmente
        if (!$this->validarFormatoCnpj($cnpj)) {
            $this->mensagem = 'CNPJ inválido';
            return false;
        }

        // Consulta a BrasilAPI
        $consulta = $this->brasilApiService->consultarCnpj($cnpj);

        if (!$consulta['success']) {
            $this->mensagem = $consulta['mensagem'] ?? 'Erro na consulta do CNPJ';
            return false;
        }

        if (!$consulta['ativo']) {
            $this->mensagem = 'CNPJ não está ativo na Receita Federal';
            return false;
        }

        return true;
    }

    public function message()
    {
        return $this->mensagem;
    }

    private function validarFormatoCnpj($cnpj)
    {
        if (strlen($cnpj) != 14) {
            return false;
        }

        // Verifica se não é uma sequência de números iguais
        if (preg_match('/^(\d)\1+$/', $cnpj)) {
            return false;
        }

        // Cálculo do primeiro dígito verificador
        $soma = 0;
        $pesos = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

        for ($i = 0; $i < 12; $i++) {
            $soma += $cnpj[$i] * $pesos[$i];
        }

        $resto = $soma % 11;
        $digito1 = ($resto < 2) ? 0 : 11 - $resto;

        if ($cnpj[12] != $digito1) {
            return false;
        }

        // Cálculo do segundo dígito verificador
        $soma = 0;
        $pesos = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

        for ($i = 0; $i < 13; $i++) {
            $soma += $cnpj[$i] * $pesos[$i];
        }

        $resto = $soma % 11;
        $digito2 = ($resto < 2) ? 0 : 11 - $resto;

        return $cnpj[13] == $digito2;
    }
}
