<?php

use Illuminate\Support\Facades\Route;
use App\Models\Ong;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

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
        return redirect()->route('perfil'); // Redireciona para a rota do perfil

    } catch (\Exception $e) {
        logger()->error('Erro no cadastro:', ['error' => $e->getMessage()]);
        return back()->withInput()->withErrors(['error' => $e->getMessage()]);
    }
})->name('ong.register');

Route::middleware(['auth'])->group(function () {
    Route::get('/perfil', function () {
        $user = auth()->user();
        $ong = $user->ong; // Carrega os dados da ONG associada
        
        return view('perfil', compact('user', 'ong'));
    })->name('perfil');

    // Rota para exibir o formulário de edição
    Route::get('/perfil/editar', function () {
        $user = auth()->user();
        $ong = $user->ong;
        return view('editar-perfil', compact('user', 'ong'));
    })->name('perfil.editar');

    // Rota para processar a edição
    Route::put('/perfil/atualizar', function () {
        $user = auth()->user();
        $ong = $user->ong;
    
        $validated = request()->validate([
            'nome' => 'required|string|max:100',
            'cnpj' => 'required|string|max:20|unique:ong,cnpj,'.$ong->id,
            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('ong', 'email')->ignore($ong->id),
                Rule::unique('users', 'email')->ignore($user->id)
            ],
            'telefone' => 'required|string|max:15',
            'endereco' => 'required|string|max:255',

            // Novas regras para senha (opcional)
            'current_password' => [
                'sometimes',
                'required_with:new_password',
                function ($attribute, $value, $fail) use ($user) {
                    if (!Hash::check($value, $user->password)) {
                        $fail('A senha atual está incorreta.');
                    }
                }
            ],
            'new_password' => [
                'sometimes',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
        ]);
    
        DB::transaction(function () use ($validated, $user, $ong) {
            // Atualiza a ONG
            $ong->update($validated);
            
            // Atualiza o usuário com os mesmos dados relevantes
            $user->update([
                'name' => $validated['nome'],  // Atualiza o nome no usuário
                'email' => $validated['email'], // Atualiza o email no usuário
                // Atualiza a senha se for fornecida
                'password' => isset($validated['new_password']) 
                ? Hash::make($validated['new_password'])
                : $user->password
            ]);
        });
    
        return redirect()->route('perfil')->with('success', 'Perfil atualizado com sucesso!');
    })->name('perfil.atualizar');
    
});



