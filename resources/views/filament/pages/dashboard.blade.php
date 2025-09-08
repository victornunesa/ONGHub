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

    {{-- Gráfico Intenções e Pedidos --}}
    <div class="mt-10 bg-white dark:bg-gray-900 shadow rounded-lg p-6">
        <h2 class="text-xl font-bold mb-4 text-purple-600 dark:text-purple-400 text-center">
            Intenções vs Pedidos de Doação (Últimos 6 meses)
        </h2>

        <div class="relative" style="height: 350px;">
            <canvas id="grafico-linha" class="absolute inset-0 w-full h-full max-w-4xl mx-auto"></canvas>
        </div>
    </div>

    {{-- Movimentações de Estoque --}}
    <form method="GET" class="mt-10 mb-4 max-w-4xl mx-auto flex items-center space-x-4">
        <label for="item" class="font-semibold text-gray-700 dark:text-gray-300">Filtrar item:</label>
        <select name="item" id="item" onchange="this.form.submit()" class="border rounded px-3 py-2 dark:bg-gray-700 dark:text-gray-200">
            <option value="">Todos os itens</option>
            @foreach ($itens as $item)
                <option value="{{ $item }}" @selected($item == $itemSelecionado)>{{ $item }}</option>
            @endforeach
        </select>
        @if ($itemSelecionado)
            <a href="{{ url()->current() }}" class="text-sm text-red-500 hover:underline">Limpar filtro</a>
        @endif
    </form>

    {{-- Gráfico Movimentações --}}
    <div class="mt-10 bg-white dark:bg-gray-900 shadow rounded-lg p-6">
        <h2 class="text-xl font-bold mb-4 text-green-600 dark:text-green-400 text-center">
            Quantidade de Movimentações de Estoque (Últimos 6 meses)
        </h2>

        <div class="relative" style="height: 350px;">
            <canvas id="grafico-estoque" class="absolute inset-0 w-full h-full max-w-4xl mx-auto"></canvas>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const isDarkMode = document.documentElement.classList.contains('dark');

            // Gráfico Intenções vs Pedidos
            const ctx = document.getElementById('grafico-linha').getContext('2d');
            const graficoLinha = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($graficoMeses),
                    datasets: [
                        {
                            label: 'Intenções de Doação',
                            data: @json($graficoIntencoesTotais),
                            borderColor: '#f97316',
                            backgroundColor: 'rgba(249, 115, 22, 0.1)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                        },
                        {
                            label: 'Pedidos de Doação',
                            data: @json($graficoPedidosTotais),
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            labels: {
                                color: isDarkMode ? '#d1d5db' : '#374151',
                            }
                        },
                        tooltip: {
                            backgroundColor: isDarkMode ? '#1f2937' : '#ffffff',
                            titleColor: isDarkMode ? '#f3f4f6' : '#111827',
                            bodyColor: isDarkMode ? '#d1d5db' : '#1f2937',
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                color: isDarkMode ? '#d1d5db' : '#374151',
                            },
                            grid: {
                                color: isDarkMode ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.05)'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                                color: isDarkMode ? '#d1d5db' : '#374151',
                            },
                            grid: {
                                color: isDarkMode ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.05)'
                            }
                        }
                    }
                }
            });

            // Gráfico Movimentações de Estoque
            const ctxEstoque = document.getElementById('grafico-estoque').getContext('2d');
            const graficoEstoque = new Chart(ctxEstoque, {
                type: 'line',
                data: {
                    labels: @json($mesesEstoque),
                    datasets: [
                        {
                            label: 'Entradas',
                            data: @json($estoqueEntradas),
                            borderColor: '#22c55e',
                            backgroundColor: 'rgba(34, 197, 94, 0.1)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                        },
                        {
                            label: 'Saídas',
                            data: @json($estoqueSaidas),
                            borderColor: '#ef4444',
                            backgroundColor: 'rgba(239, 68, 68, 0.1)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            labels: {
                                color: isDarkMode ? '#d1d5db' : '#374151',
                            }
                        },
                        tooltip: {
                            backgroundColor: isDarkMode ? '#1f2937' : '#ffffff',
                            titleColor: isDarkMode ? '#f3f4f6' : '#111827',
                            bodyColor: isDarkMode ? '#d1d5db' : '#1f2937',
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                color: isDarkMode ? '#d1d5db' : '#374151',
                            },
                            grid: {
                                color: isDarkMode ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.05)'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                                color: isDarkMode ? '#d1d5db' : '#374151',
                            },
                            grid: {
                                color: isDarkMode ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.05)'
                            }
                        }
                    }
                }
            });

            // Atualiza as cores quando troca tema
            const observer = new MutationObserver(() => {
                const dark = document.documentElement.classList.contains('dark');
                [graficoLinha, graficoEstoque].forEach(chart => {
                    chart.options.plugins.legend.labels.color = dark ? '#d1d5db' : '#374151';
                    chart.options.plugins.tooltip.backgroundColor = dark ? '#1f2937' : '#ffffff';
                    chart.options.plugins.tooltip.titleColor = dark ? '#f3f4f6' : '#111827';
                    chart.options.plugins.tooltip.bodyColor = dark ? '#d1d5db' : '#1f2937';
                    chart.options.scales.x.ticks.color = dark ? '#d1d5db' : '#374151';
                    chart.options.scales.x.grid.color = dark ? 'rgba(255,255,255,0.1)' :
                    chart.options.scales.x.grid.color = dark ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.05)';
                    chart.options.scales.y.ticks.color = dark ? '#d1d5db' : '#374151';
                    chart.options.scales.y.grid.color = dark ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.05)';
                    chart.update();
                });
            });

            observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
        </script>
    @endpush

</x-filament::page>
