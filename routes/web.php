<?php

use Illuminate\Support\Facades\Route;
use App\Models\Ong;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/cadastro', function () {
    return view('user-registration'); 
})->name('user.registration');

Route::post('/cadastro', function () {
    $validated = request()->validate([
        'nome' => 'required|string|max:100',
        'cnpj' => 'required|string|max:20|unique:ong,cnpj',
        'email' => 'required|email|max:100|unique:users,email',
        'telefone' => 'required|string|max:15',
        'endereco' => 'required|string|max:255',
        'password' => [
            'required',
            'confirmed',
            Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
        ],
    ]);

    DB::transaction(function () use ($validated) {
        // 1. Cria a ONG primeiro
        $ong = Ong::create([
            'nome' => $validated['nome'],
            'cnpj' => $validated['cnpj'],
            'email' => $validated['email'],
            'telefone' => $validated['telefone'],
            'endereco' => $validated['endereco'],
            'status' => 'ativo'
        ]);

        // 2. Cria o usuário associado à ONG
        User::create([
            'name' => $validated['nome'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'tipo' => 'ong',
            'status' => 'ativo',
            'ong_id' => $ong->id // Associa ao ID da ONG criada
        ]);
    });

    return redirect('/')->with('success', 'ONG cadastrada com sucesso!');
})->name('ong.register');



