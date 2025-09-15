<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class ReceitaService
{
    protected $client;
    protected $baseUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->baseUrl = 'https://receitaws.com.br/v1/cnpj/';
    }

    public function consultarCnpj($cnpj)
    {
        try {
            $cnpj = preg_replace('/[^0-9]/', '', $cnpj);

            $response = $this->client->get($this->baseUrl . $cnpj, [
                'timeout' => 30,
                'headers' => [
                    'Accept' => 'application/json',
                ]
            ]);

            $data = json_decode($response->getBody(), true);

            return [
                'ativo' => isset($data['status']) && $data['status'] === 'OK',
                'dados' => $data,
                'mensagem' => $data['message'] ?? null
            ];

        } catch (\Exception $e) {
            Log::error('Erro ao consultar CNPJ: ' . $e->getMessage());
            return [
                'ativo' => false,
                'erro' => 'Serviço indisponível no momento'
            ];
        }
    }
}
