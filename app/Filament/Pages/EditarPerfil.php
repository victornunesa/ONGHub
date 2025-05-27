<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Filament\Notifications\Notification;


class EditarPerfil extends Page
{
    //protected static ?string $navigationIcon = 'heroicon-o-pencil';
    protected static string $view = 'filament.pages.editar-perfil';

    protected static ?string $title = 'Editar Perfil';
    //protected static ?string $navigationLabel = null; // Oculta no menu
    protected static ?string $slug = 'perfil/editar'; // URL acessível em /admin/perfil/editar

    public $nome;
    public $cnpj;
    public $email;
    public $telefone;
    public $endereco;

    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    public function mount(): void
    {
        $ong = auth()->user()->ong;

        $this->nome = $ong->nome;
        $this->cnpj = $ong->cnpj;
        $this->email = $ong->email;
        $this->telefone = $ong->telefone;
        $this->endereco = $ong->endereco;

        /*Notification::make()
            ->title('Página carregada!')
            ->info()
            ->send();*/
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false; // Impede que apareça na sidebar
    }


    public function submit()
    {
        $user = auth()->user();
        $ong = $user->ong;

        $validated = $this->validate([
            'nome' => 'required|string|max:100',
            'cnpj' => ['required', 'string', 'max:20', Rule::unique('ong', 'cnpj')->ignore($ong->id)],
            'email' => [
                'required', 'email', 'max:100',
                Rule::unique('ong', 'email')->ignore($ong->id),
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'telefone' => 'required|string|max:15',
            'endereco' => 'required|string|max:255',
            'current_password' => [
                'nullable',
                function ($attribute, $value, $fail) use ($user) {
                    if ($value && !Hash::check($value, $user->password)) {
                        $fail('A senha atual está incorreta.');
                    }
                }
            ],
            'new_password' => [
                'nullable', 'confirmed',
                Password::min(8)->letters()->mixedCase()->numbers()->symbols()
            ]
        ]);

        \DB::transaction(function () use ($validated, $user, $ong) {
            $ong->update($validated);

            $user->update([
                'name' => $validated['nome'],
                'email' => $validated['email'],
                'password' => $validated['new_password']
                    ? Hash::make($validated['new_password'])
                    : $user->password,
            ]);
        });

        Notification::make()
            ->title('Perfil atualizado com sucesso!')
            ->success()
            ->send();

        $this->current_password = null;
        $this->new_password = null;
        $this->new_password_confirmation = null;


    }
}

