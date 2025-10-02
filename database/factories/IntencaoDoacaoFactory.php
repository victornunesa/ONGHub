<?php

namespace Database\Factories;

use App\Models\Ong;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\IntencaoDoacao>
 */
class IntencaoDoacaoFactory extends Factory
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
            'email_solicitante' => $this->faker->safeEmail(),
            'telefone_solicitante' => $this->faker->phoneNumber(),
            'ong_desejada' => Ong::factory(),
            'descricao' => $this->faker->randomElement(['Arroz', 'Feijão', 'Macarrão', 'Carne', 'Macarrão']),
            'tipo' => 'Alimentos',
            'quantidade' => $this->faker->numberBetween(1, 25),
            'unidade' => $this->faker->randomElement(['kg', 'lata', 'litro']),
            'status' => 'registrada',
            'data_pedido' => now()
        ];
    }

    public function withOng($ongId = null): static
    {
        return $this->state(function () use ($ongId) {
            return [
                'ong_desejada' => $ongId ?? Ong::factory()
            ];
        });
    }

    public function withStatus($status = null): static
    {
        return $this->state(function () use ($status) {
            return [
                'status' => $status ?? 'registrada'
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
