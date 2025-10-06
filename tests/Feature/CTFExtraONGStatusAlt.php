<?php

use App\Models\User;
use App\Models\Ong;
use Illuminate\Foundation\Testing\RefreshDatabase; 
use Tests\TestCase;


uses(Tests\TestCase::class, RefreshDatabase::class)->in(__DIR__);


test('CTFExtra – Inativa a ONG ao chamar o serviço de inativação', function () {
    $ong = Ong::factory()->create([
        'status' => 'ativo',
    ]);

    $user = User::factory()->withOng($ong->id)->create([
        'tipo' => 'Admin',   
        'status' => 'ativo', 
    ]);

    expect($ong->status)->toBe('ativo');

    $this->actingAs($user)->patch(route('perfil.inativar'));

    $this->assertDatabaseHas('ong', [
        'id' => $ong->id,
        'status' => 'inativo',
    ]);
});



