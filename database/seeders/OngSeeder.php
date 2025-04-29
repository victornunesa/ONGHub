<?php

namespace Database\Seeders;

use App\Models\Ong;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OngSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Ong::factory()
        ->hasUsuarios(1, [
            'email' => 'test@example.com',
        ])
        ->create();

        Ong::factory()
        ->count(5)
        ->hasUsuarios(1)
        ->create();
    }
}
