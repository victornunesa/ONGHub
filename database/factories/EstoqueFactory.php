<?php

namespace Database\Factories;

use App\Models\Ong;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Estoque>
 */
class EstoqueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ong_id' => Ong::factory(), // cria uma ONG associada automaticamente
            'nome_item' => $this->faker->randomElement(['Arroz', 'Feijão', 'Macarrão', 'Leite']),
            'quantidade' => $this->faker->numberBetween(1, 100),
            'quantidade_solicitada' => 0,
            'unidade' => $this->faker->randomElement(['kg', 'l', 'pacote', 'unidade']),
            'data_atualizacao' => now(),
            'data_validade' => $this->faker->dateTimeBetween('now', '+1 year')
        ];
    }

    public function withOng($ongId = null): static
    {
        return $this->state(function () use ($ongId) {
            return [
                'ong_id' => $ongId ?? Ong::factory()
            ];
        });
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
