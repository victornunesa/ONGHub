<x-filament::page>
    <x-filament::card class="max-w-3xl mx-auto space-y-6">

        <form wire:submit.prevent="submit" class="space-y-8">
            {{-- Campos principais agrupados com espaçamento --}}
            <div class="space-y-6">
                {{-- Nome da ONG --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nome da ONG</label>
                    <input
                        type="text"
                        wire:model.defer="nome"
                        class="w-full rounded-md border border-gray-300 px-3 py-2 focus:border-primary-600 focus:ring focus:ring-primary-300 focus:ring-opacity-50"
                        placeholder="Digite o nome da ONG"
                        required
                    />
                </div>

                {{-- CNPJ --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">CNPJ</label>
                    <input
                        type="text"
                        wire:model.defer="cnpj"
                        class="w-full rounded-md border border-gray-300 px-3 py-2 focus:border-primary-600 focus:ring focus:ring-primary-300 focus:ring-opacity-50"
                        placeholder="00.000.000/0000-00"
                        required
                    />
                </div>

                {{-- E-mail --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">E-mail</label>
                    <input
                        type="email"
                        wire:model.defer="email"
                        class="w-full rounded-md border border-gray-300 px-3 py-2 focus:border-primary-600 focus:ring focus:ring-primary-300 focus:ring-opacity-50"
                        placeholder="exemplo@dominio.com"
                        required
                    />
                </div>

                {{-- Telefone --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Telefone</label>
                    <input
                        type="text"
                        wire:model.defer="telefone"
                        class="w-full rounded-md border border-gray-300 px-3 py-2 focus:border-primary-600 focus:ring focus:ring-primary-300 focus:ring-opacity-50"
                        placeholder="(00) 00000-0000"
                        required
                    />
                </div>

                {{-- Endereço --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Endereço Completo</label>
                    <textarea
                        wire:model.defer="endereco"
                        rows="3"
                        class="w-full rounded-md border border-gray-300 px-3 py-2 resize-none focus:border-primary-600 focus:ring focus:ring-primary-300 focus:ring-opacity-50"
                        placeholder="Digite o endereço completo"
                        required
                    ></textarea>
                </div>
            </div>

            <hr class="my-8 border-t border-gray-200" />

            {{-- Alterar Senha --}}
            <h3 class="text-lg font-semibold mb-4">Alterar Senha</h3>

            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Senha Atual</label>
                    <input
                        type="password"
                        wire:model.defer="current_password"
                        class="w-full rounded-md border border-gray-300 px-3 py-2 focus:border-primary-600 focus:ring focus:ring-primary-300 focus:ring-opacity-50"
                        placeholder="Senha atual"
                    />
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nova Senha</label>
                    <input
                        type="password"
                        wire:model.defer="new_password"
                        class="w-full rounded-md border border-gray-300 px-3 py-2 focus:border-primary-600 focus:ring focus:ring-primary-300 focus:ring-opacity-50"
                        placeholder="Nova senha"
                    />
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Confirme a Nova Senha</label>
                    <input
                        type="password"
                        wire:model.defer="new_password_confirmation"
                        class="w-full rounded-md border border-gray-300 px-3 py-2 focus:border-primary-600 focus:ring focus:ring-primary-300 focus:ring-opacity-50"
                        placeholder="Confirme a nova senha"
                    />
                </div>
            </div>

            <p class="text-sm text-gray-500 mt-3">
                A nova senha deve conter pelo menos 8 caracteres, letras maiúsculas e minúsculas, números e símbolos.
            </p>

            {{-- Botões --}}
            <div class="flex justify-end mt-8 gap-4">
                <x-filament::button
                    color="secondary"
                    tag="a"
                    href="{{ route('filament.admin.pages.perfil') }}"
                >
                    Cancelar
                </x-filament::button>

                <x-filament::button type="submit" color="info">
                    Salvar Alterações
                </x-filament::button>
            </div>
        </form>
    </x-filament::card>

    <hr class="my-6 border-t border-gray-200" />

    <x-filament::card>
        <h3 class="text-lg font-bold">Inativar Conta</h3>
        <p class="text-sm text-gray-500 mb-2">Você não poderá mais acessar até que um administrador reative sua conta.</p>

        <form method="POST" action="{{ route('perfil.inativar') }}" onsubmit="return confirm('Deseja realmente inativar sua conta?')">
            @csrf
            @method('PATCH')
            <x-filament::button color="warning" type="submit">Inativar Conta</x-filament::button>
        </form>
    </x-filament::card>

    <hr class="my-6 border-t border-gray-200" />

    <x-filament::card>
        <h3 class="text-lg font-bold">Excluir Conta</h3>
        <p class="text-sm text-gray-500 mb-2">Esta ação é permanente. Sua conta será apagada definitivamente.</p>

        <form method="POST" action="{{ route('perfil.deletar') }}" onsubmit="return confirm('Tem certeza que deseja excluir sua conta?')">
            @csrf
            @method('DELETE')
            <x-filament::button color="danger" type="submit">Excluir Conta</x-filament::button>
        </form>
    </x-filament::card>
</x-filament::page>


