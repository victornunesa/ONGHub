php artisan make:service BrasilApiService
<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class BrasilApiService
{
    protected $client;
    protected $baseUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->baseUrl = 'https://brasilapi.com.br/api/cnpj/v1/';
    }

    /**
     * Consulta CNPJ na BrasilAPI
     */
    public function consultarCnpj($cnpj)
    {
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);

        // Verifica formato básico
        if (strlen($cnpj) !== 14) {
            return [
                'ativo' => false,
                'mensagem' => 'CNPJ deve ter 14 dígitos',
                'success' => false
            ];
        }

        // Cache de 24 horas para evitar consultas repetidas
        $cacheKey = "cnpj_{$cnpj}";

        return Cache::remember($cacheKey, 86400, function () use ($cnpj) {
            try {
                $response = $this->client->get($this->baseUrl . $cnpj, [
                    'timeout' => 15,
                    'connect_timeout' => 10,
                    'headers' => [
                        'Accept' => 'application/json',
                        'User-Agent' => 'Laravel-ONG-App/1.0'
                    ]
                ]);

                $data = json_decode($response->getBody(), true);

                // Verifica se a consulta foi bem sucedida
                if (isset($data['cnpj'])) {
                    return [
                        'ativo' => $this->isCnpjAtivo($data),
                        'situacao' => $data['descricao_situacao_cadastral'] ?? 'DESCONHECIDA',
                        'dados' => $data,
                        'mensagem' => 'Consulta realizada com sucesso',
                        'success' => true,
                        'fonte' => 'brasilapi'
                    ];
                }

                return [
                    'ativo' => false,
                    'mensagem' => 'CNPJ não encontrado na base de dados',
                    'success' => false,
                    'fonte' => 'brasilapi'
                ];

            } catch (\GuzzleHttp\Exception\ClientException $e) {
                $statusCode = $e->getResponse()->getStatusCode();

                if ($statusCode === 404) {
                    return [
                        'ativo' => false,
                        'mensagem' => 'CNPJ não encontrado na Receita Federal',
                        'success' => false,
                        'fonte' => 'brasilapi'
                    ];
                }

                if ($statusCode === 429) {
                    return [
                        'ativo' => false,
                        'mensagem' => 'Limite de consultas excedido. Tente novamente em alguns instantes.',
                        'success' => false,
                        'fonte' => 'brasilapi'
                    ];
                }

                Log::error("Erro BrasilAPI Client: {$e->getMessage()}");
                return [
                    'ativo' => false,
                    'mensagem' => 'Erro na consulta do CNPJ',
                    'success' => false,
                    'fonte' => 'brasilapi'
                ];

            } catch (\GuzzleHttp\Exception\ServerException $e) {
                Log::error("Erro BrasilAPI Server: {$e->getMessage()}");
                return [
                    'ativo' => false,
                    'mensagem' => 'Serviço temporariamente indisponível',
                    'success' => false,
                    'fonte' => 'brasilapi'
                ];

            } catch (\Exception $e) {
                Log::error("Erro geral BrasilAPI: {$e->getMessage()}");
                return [
                    'ativo' => false,
                    'mensagem' => 'Erro na conexão com o serviço de consulta',
                    'success' => false,
                    'fonte' => 'brasilapi'
                ];
            }
        });
    }

    /**
     * Verifica se o CNPJ está ativo baseado nos dados retornados
     */
    protected function isCnpjAtivo($dados)
    {
        // Verifica pela situação cadastral
        $situacao = strtoupper($dados['descricao_situacao_cadastral'] ?? '');

        $situacoesAtivas = [
            'ATIVA',
            'NULA',
            'SUSPENSA',
            'INAPTA',
            'BAIXADA'
        ];

        // Considera ativo se não estiver baixada
        return $situacao !== 'BAIXADA' && in_array($situacao, $situacoesAtivas);
    }

    /**
     * Limpa o cache de um CNPJ específico
     */
    public function limparCache($cnpj)
    {
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);
        return Cache::forget("cnpj_{$cnpj}");
    }
}
