<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ong>
 */
class OngFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome' => 'ONG 1' ,
            'cnpj' => fake()->numerify("##############"),
            'email' => fake()->email(),
            'telefone' => '1234567',
            'endereco' => fake()->address(),
            'status' => 'ativo'
        ];
    }
}
