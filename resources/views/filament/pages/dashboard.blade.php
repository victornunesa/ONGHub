<x-filament::page>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Intenções de Doação --}}
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-bold mb-6 text-amber-600">
                Intenções de Doação <span class="text-gray-500">({{ $totalIntencoes }})</span>
            </h2>

            @forelse ($intencoesRecentes as $intencao)
                <div class="border rounded-lg p-4 mb-4 shadow-sm bg-gray-50">
                    <p class="text-gray-600 mb-2">
                        {{ $intencao->descricao }}
                    </p>
                    <p class="text-xs text-gray-500 text-right">
                        {{ \Carbon\Carbon::parse($intencao->data_pedido)->format('d/m/Y') }}
                    </p>
                </div>
            @empty
                <p class="text-gray-500 italic">Nenhuma intenção registrada.</p>
            @endforelse
        </div>

        {{-- Pedidos de Doação --}}
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-bold mb-6 text-blue-600">
                Pedidos de Doação <span class="text-gray-500">({{ $totalPedidos }})</span>
            </h2>

            @forelse ($pedidosRecentes as $pedido)
                <div class="border rounded-lg p-4 mb-4 shadow-sm bg-gray-50">
                    <p class="text-gray-600 mb-2">
                        {{ $pedido->descricao }}
                    </p>
                    <p class="text-xs text-gray-500 text-right">
                        {{ \Carbon\Carbon::parse($pedido->data_pedido)->format('d/m/Y') }}
                    </p>
                </div>
            @empty
                <p class="text-gray-500 italic">Nenhum pedido registrado.</p>
            @endforelse
        </div>

    </div>
</x-filament::page>



