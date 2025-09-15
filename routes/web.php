<?php

use App\Mail\PedidoCriadoMail;
use Illuminate\Support\Facades\Route;
use App\Models\Ong;
use App\Models\User;
use App\Models\IntencaoDoacao;
use App\Models\PedidoDoacao;
use App\Rules\CnpjAtivo;
use App\Services\ReceitaService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/admin');
    }

    return view('welcome');
});

Route::get('/reativar-conta', function () {
    return view('reativar-conta');
})->name('reativar.conta');

Route::post('/reativar-conta', function () {
    $credentials = request()->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = \App\Models\User::where('email', $credentials['email'])
        ->where('status', 'inativo')
        ->first();

    if (!$user || !\Illuminate\Support\Facades\Hash::check($credentials['password'], $user->password)) {
        return back()->withErrors(['email' => 'Credenciais inválidas ou conta já está ativa.'])->withInput();
    }

    \Illuminate\Support\Facades\DB::transaction(function () use ($user) {
        $user->update(['status' => 'ativo']);
        $user->ong()->update(['status' => 'ativo']);
    });

    Auth::guard(config('filament.auth.guard'))->login($user);

    return redirect("/admin")->with('success', 'Conta reativada com sucesso!');
})->name('reativar.conta.processar');


Route::get('/cadastro', function () {
    return view('user-registration');
})->name('user.registration');

Route::post('/cadastro', function () {
    try {
        $validated = request()->validate([
            'nome' => 'required|string|max:100',
            'cnpj' => [
                    'required',
                    'string',
                    'max:20',
                    'unique:ong,cnpj',
                    new CnpjAtivo()
            ],
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

        //auth()->login($user); // Autentica o usuário
        Auth::login($user);
        return redirect('/admin');

    } catch (\Exception $e) {
        // dd($e->errors());
        logger()->error('Erro no cadastro:', ['error' => $e->getMessage()]);
        return back()->withInput()->withErrors(['error' => $e->getMessage()]);
    }
})->name('ong.register');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', function () {
    $credentials = request()->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    $user = \App\Models\User::where('email', $credentials['email'])->first();

    if (!$user || !Hash::check($credentials['password'], $user->password)) {
        return back()->withErrors(['email' => 'Credenciais inválidas.']);
    }

    if ($user->status !== 'ativo') {
        return back()->withErrors(['email' => 'Sua conta está inativa.']);
    }

    Auth::login($user);
    request()->session()->regenerate();
    return redirect('/admin');
});

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate(); // Invalida a sessão
    request()->session()->regenerateToken(); // Regenera o token CSRF
    return redirect('/login');
})->name('logout');

// Adicione esta rota com as outras rotas GET
Route::get('/doacao', function () {
    return view('pedidodoacao-form');
})->name('pedidodoacao.create');

// Adicione esta rota para processar o formulário
Route::post('/doacao', function () {

    $codigo = 'PD-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));

    $validated = request()->validate([
        'nome_solicitante' => 'required|string|max:100',
        'email_solicitante' => 'required|email|max:100',
        'telefone_solicitante' => 'required|string|max:15',
        'itens' => 'required|array|min:1',
        'cpf' => 'required|cpf',
        'itens.*.descricao' => 'required|string|max:255',
        'itens.*.quantidade' => 'required|numeric|min:0.1', // Permite valores decimais
        'itens.*.unidade' => 'required|string|in:kg,g,L,ml,un,cx,pct,lata,saca,dz,band,fardo,vidro',
    ]);

    $dadosPessoais = [
        'nome_solicitante' => $validated['nome_solicitante'],
        'email_solicitante' => $validated['email_solicitante'],
        'telefone_solicitante' => $validated['telefone_solicitante'],
        'tipo' => 'Alimentos',
        'status' => 'Registrada',
        'data_pedido' => now(),
    ];

    foreach ($validated['itens'] as $item) {
        $pedido_criado = \App\Models\PedidoDoacao::create([
            'nome_solicitante' => $validated['nome_solicitante'],
            'email_solicitante' => $validated['email_solicitante'],
            'telefone_solicitante' => $validated['telefone_solicitante'],
            'cpf' => $validated['cpf'],
            'codigo' => $codigo,
            'tipo' => 'Alimentos',
            'status' => 'Registrada',
            'data_pedido' => now(),
            'descricao' => $item['descricao'],
            'quantidade' => $item['quantidade'],
            'unidade' => $item['unidade'] // Garantindo que pegue a unidade do item
        ]);
    }

    Mail::to($validated['email_solicitante'])->send(new PedidoCriadoMail($pedido_criado));

    return redirect('/')->with('success', 'Doação registrada!');
})->name('doacao.store');

// Rota para exibir o formulário
Route::get('/intencao-doacao', function () {
    $ongs = \App\Models\Ong::where('status', 'ativo')->get();
    return view('intencaodoacao-form', ['ongs' => $ongs]);
})->name('intencaodoacao.create');

Route::get('/pedido/externo/{codigo}', function ($codigo) {
    $pedidos = PedidoDoacao::where('codigo', $codigo)->get();

    return view('pedido-externo.visualizar', ['pedidos' => $pedidos]);
})->name('pedido.visualizar-externo');

// Rota para processar o formulário
Route::post('/intencao-doacao', function () {
    $validated = request()->validate([
        'nome_solicitante' => 'required|string|max:100',
        'email_solicitante' => 'required|email|max:100',
        'telefone_solicitante' => 'required|string|max:15',
        'ong_desejada' => 'required|exists:ong,id',  // Corrigido para 'ong' (nome da tabela)
        'itens' => 'required|array|min:1',
        'itens.*.descricao' => 'required|string|max:255',
        'itens.*.quantidade' => 'required|numeric|min:0.1',
        'itens.*.unidade' => 'required|string|in:kg,g,L,ml,un,cx,pct,lata,saca,dz,band,fardo,vidro',
    ]);

    foreach ($validated['itens'] as $item) {
        \App\Models\IntencaoDoacao::create([
            'nome_solicitante' => $validated['nome_solicitante'],
            'email_solicitante' => $validated['email_solicitante'],
            'telefone_solicitante' => $validated['telefone_solicitante'],
            'ong_desejada' => $validated['ong_desejada'],
            'tipo' => 'Alimentos',
            'status' => 'Registrada',
            'data_pedido' => now(),
            'descricao' => $item['descricao'],
            'quantidade' => $item['quantidade'],
            'unidade' => $item['unidade']
        ]);
    }

    return redirect('/')->with('success', 'Sua intenção foi registrada!');
})->name('intencao.store');

// routes/web.php
Route::redirect('/perfil', '/admin/perfil')->middleware(['auth']);

Route::middleware(['auth'])->group(function () {
    Route::get('/perfil', function () {
        $user = auth()->user();
        $ong = $user->ong; // Carrega os dados da ONG associada

        return view('perfil', compact('user', 'ong'));
    })->name('perfil');

    // Rota para exibir o formulário de edição
    /*Route::get('/perfil/editar', function () {
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
    })->name('perfil.atualizar');*/

    Route::delete('/perfil/deletar', function () {
        $user = auth()->user();

        DB::transaction(function () use ($user) {
            $user->delete();        // Deleta o próprio usuário
            $user->ong()->delete(); // Deleta a ONG associada
        });

        Auth::logout();
        request()->session()->invalidate(); // Invalida a sessão
        request()->session()->regenerateToken(); // Regenera o token CSRF

        return redirect('/')->with('success', 'Conta excluída com sucesso.');
    })->name('perfil.deletar');

    Route::patch('/perfil/inativar', function () {
        $user = auth()->user();

        DB::transaction(function () use ($user) {
            $user->update(['status' => 'inativo']);
            $user->ong()->update(['status' => 'inativo']);
        });

        Auth::logout();
        request()->session()->invalidate(); // Invalida a sessão
        request()->session()->regenerateToken(); // Regenera o token CSRF

        return redirect('/')->with('success', 'Sua conta foi inativada com sucesso.');
    })->name('perfil.inativar');

    Route::get('/dashboard/estoque-dados', function () {
        $ong = auth()->user()->ong;
        $itemSelecionado = request()->query('item');

        $mesesEstoqueChaves = collect(range(0, 5))->map(function ($i) {
            return \Carbon\Carbon::now()->subMonths($i)->format('Y-m');
        })->reverse()->values();

        $mesesEstoqueFormatadosJS = $mesesEstoqueChaves->map(function ($mes) {
            return \Carbon\Carbon::createFromFormat('Y-m', $mes)->translatedFormat('M/Y');
        });

        $query = DB::table('doacao')
            ->select(
                DB::raw("DATE_FORMAT(data_doacao, '%Y-%m') as mes"),
                'status',
                DB::raw('COUNT(*) as total')
            )
            ->whereIn('status', ['Entrada', 'Saida'])
            ->whereBetween('data_doacao', [
                \Carbon\Carbon::now()->subMonths(5)->startOfMonth(),
                \Carbon\Carbon::now()->endOfMonth()
            ]);

        if ($itemSelecionado) {
            $query->where('descricao', $itemSelecionado);
        }

        $movimentacoes = $query->groupBy('mes', 'status')->get();

        $entradas = [];
        $saidas = [];

        foreach ($mesesEstoqueChaves as $mes) {
            $entrada = $movimentacoes->first(fn($m) => $m->mes === $mes && strtolower(trim($m->status)) === 'entrada');
            $saida = $movimentacoes->first(fn($m) => $m->mes === $mes && strtolower(trim($m->status)) === 'saida');

            $entradas[] = $entrada->total ?? 0;
            $saidas[] = $saida->total ?? 0;
        }

        return response()->json([
            'labels' => $mesesEstoqueFormatadosJS,
            'entradas' => $entradas,
            'saidas' => $saidas,
        ]);
    })->name('dashboard.estoque.dados');


});



