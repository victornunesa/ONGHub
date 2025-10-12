<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PedidoDoacao>
 */
class PedidoDoacaoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome_solicitante' => $this->faker->name(),
            'email_solicitante' => $this->faker->unique()->safeEmail(),
            'telefone_solicitante' => $this->faker->phoneNumber(),
            'descricao' => $this->faker->randomElement(['Arroz', 'Feijão', 'Leite', 'Macarrão']),
            'quantidade' => $this->faker->randomFloat(2, 1, 50), // entre 1 e 50 unidades
            'status' => 'Registrada',
            'data_pedido' => now(),
            'tipo' => $this->faker->randomElement(['Alimentos', 'Higiene', 'Limpeza']),
            'unidade' => $this->faker->randomElement(['kg', 'l', 'unidade', 'pacote']),
            'cpf' => '69161677000', // se estiver usando faker-php-br
            'codigo' => strtoupper(Str::random(10)), // código único
        ];
    }

    public function withQuantidade($quantidade = null): static
    {
        return $this->state(function () use ($quantidade) {
            return [
                'quantidade' => $quantidade ?? $this->faker->numberBetween(1, 25)
            ];
        });
    }
}
