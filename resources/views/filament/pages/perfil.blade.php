<x-filament::page>
    <div class="space-y-6">
        <div class="bg-white p-6 shadow rounded-lg">
            <h2 class="text-lg font-bold mb-4">Dados da ONG</h2>

            <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                <div>
                    <dt class="font-medium text-gray-600">Nome</dt>
                    <dd class="text-gray-900">{{ auth()->user()->ong->nome }}</dd>
                </div>

                <div>
                    <dt class="font-medium text-gray-600">CNPJ</dt>
                    <dd class="text-gray-900">{{ auth()->user()->ong->cnpj }}</dd>
                </div>

                <div>
                    <dt class="font-medium text-gray-600">E-mail</dt>
                    <dd class="text-gray-900">{{ auth()->user()->ong->email }}</dd>
                </div>

                <div>
                    <dt class="font-medium text-gray-600">Telefone</dt>
                    <dd class="text-gray-900">{{ auth()->user()->ong->telefone }}</dd>
                </div>

                <div class="md:col-span-2">
                    <dt class="font-medium text-gray-600">Endereço</dt>
                    <dd class="text-gray-900">{{ auth()->user()->ong->endereco }}</dd>
                </div>
            </dl>

            <div class="mt-6 flex justify-end">
                <x-filament::button
                    tag="a"
                    href="{{ url('/admin/perfil/editar') }}"
                    color="warning"
                >
                    Editar Perfil
                </x-filament::button>
            </div>
        </div>
    </div>
</x-filament::page>

