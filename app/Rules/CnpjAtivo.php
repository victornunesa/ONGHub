<?php

namespace App\Rules;

use App\Services\ReceitaService;
use Illuminate\Contracts\Validation\Rule;

class CnpjAtivo implements Rule
{
    protected $receitaService;
    protected $mensagem;

    public function __construct()
    {
        $this->receitaService = new ReceitaService();
    }

    public function passes($attribute, $value)
    {
        $cnpj = preg_replace('/[^0-9]/', '', $value);

        // Primeiro valida o formato
        if (!$this->validarFormatoCnpj($cnpj)) {
            $this->mensagem = 'CNPJ inválido';
            return false;
        }

        // Consulta a situação na Receita
        $consulta = $this->receitaService->consultarCnpj($cnpj);

        if (isset($consulta['erro'])) {
            $this->mensagem = $consulta['erro'];
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

        // Validação dos dígitos verificadores
        if (!$this->validarDigitos($cnpj)) {
            return false;
        }

        return true;
    }

    private function validarDigitos($cnpj)
    {
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
