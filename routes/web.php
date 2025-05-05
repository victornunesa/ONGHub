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
    try {
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

        // Debug: Mostra os dados validados
        logger()->info('Dados validados:', $validated);

        $ong = Ong::create([
            'nome' => $validated['nome'],
            'cnpj' => $validated['cnpj'],
            'email' => $validated['email'],
            'telefone' => $validated['telefone'],
            'endereco' => $validated['endereco'],
            'status' => 'ativo'
        ]);

        logger()->info('ONG criada:', $ong->toArray());

        $user = User::create([
            'name' => $validated['nome'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'tipo' => 'ong',
            'status' => 'ativo',
            'ong_id' => $ong->id
        ]);

        logger()->info('Usuário criado:', $user->toArray());

        auth()->login($user); // Autentica o usuário
        return redirect('/')->with('success', 'ONG cadastrada com sucesso!');

    } catch (\Exception $e) {
        logger()->error('Erro no cadastro:', ['error' => $e->getMessage()]);
        return back()->withInput()->withErrors(['error' => $e->getMessage()]);
    }
})->name('ong.register');



