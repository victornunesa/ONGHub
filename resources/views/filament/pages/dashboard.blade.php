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
    <form id="filtro-item-form" class="mt-10 mb-4 max-w-4xl mx-auto flex items-center space-x-4">
        <label for="item" class="font-semibold text-gray-700 dark:text-gray-300">Filtrar item:</label>
        <select name="item" id="item" class="border rounded px-3 py-2 dark:bg-gray-700 dark:text-gray-200">
            <option value="">Todos os itens</option>
            @foreach ($itens as $item)
                <option value="{{ $item }}" @selected($item == $itemSelecionado)>{{ $item }}</option>
            @endforeach
        </select>
        @if ($itemSelecionado)
            <button type="button" id="limpar-filtro" class="text-sm text-red-500 hover:underline">Limpar filtro</button>
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

    {{-- Gráfico Itens Vencidos --}}
    <div class="mt-10 bg-white dark:bg-gray-900 shadow rounded-lg p-6">
        <h2 class="text-xl font-bold mb-4 text-red-600 dark:text-red-400 text-center">
            Quantidade de Itens Vencidos por Mês
        </h2>

        <div class="relative" style="height: 350px;">
            <canvas id="grafico-vencidos" class="absolute inset-0 w-full h-full max-w-4xl mx-auto"></canvas>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Função para detectar se está no modo dark
                function isDarkMode() {
                    return document.documentElement.classList.contains('dark');
                }

                // Configuração padrão de cores para gráficos conforme tema
                function getChartColors() {
                    const dark = isDarkMode();
                    return {
                        legendColor: dark ? '#d1d5db' : '#374151',
                        tooltipBg: dark ? '#1f2937' : '#ffffff',
                        tooltipTitle: dark ? '#f3f4f6' : '#111827',
                        tooltipBody: dark ? '#d1d5db' : '#1f2937',
                        xTicks: dark ? '#d1d5db' : '#374151',
                        xGrid: dark ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.05)',
                        yTicks: dark ? '#d1d5db' : '#374151',
                        yGrid: dark ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.05)',
                    };
                }

                // Criar gráfico Intenções vs Pedidos
                const ctxLinha = document.getElementById('grafico-linha').getContext('2d');
                const graficoLinha = new Chart(ctxLinha, {
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
                            legend: { labels: { color: getChartColors().legendColor } },
                            tooltip: {
                                backgroundColor: getChartColors().tooltipBg,
                                titleColor: getChartColors().tooltipTitle,
                                bodyColor: getChartColors().tooltipBody,
                            }
                        },
                        scales: {
                            x: {
                                ticks: { color: getChartColors().xTicks },
                                grid: { color: getChartColors().xGrid },
                            },
                            y: {
                                beginAtZero: true,
                                ticks: { precision: 0, color: getChartColors().yTicks },
                                grid: { color: getChartColors().yGrid },
                            }
                        }
                    }
                });

                // Criar gráfico Movimentações de Estoque
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
                            legend: { labels: { color: getChartColors().legendColor } },
                            tooltip: {
                                backgroundColor: getChartColors().tooltipBg,
                                titleColor: getChartColors().tooltipTitle,
                                bodyColor: getChartColors().tooltipBody,
                            }
                        },
                        scales: {
                            x: {
                                ticks: { color: getChartColors().xTicks },
                                grid: { color: getChartColors().xGrid },
                            },
                            y: {
                                beginAtZero: true,
                                ticks: { precision: 0, color: getChartColors().yTicks },
                                grid: { color: getChartColors().yGrid },
                            }
                        }
                    }
                });

                // Criar gráfico Itens Vencidos
                const ctxVencidos = document.getElementById('grafico-vencidos').getContext('2d');
                const graficoVencidos = new Chart(ctxVencidos, {
                    type: 'bar',
                    data: {
                        labels: @json($graficoVencidosLabels),
                        datasets: [{
                            label: 'Itens Vencidos',
                            data: @json($graficoVencidosValores).map(Number), // garantir números
                            backgroundColor: 'rgba(239, 68, 68, 0.7)',
                            borderColor: 'rgba(239, 68, 68, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { labels: { color: getChartColors().legendColor } },
                            tooltip: {
                                backgroundColor: getChartColors().tooltipBg,
                                titleColor: getChartColors().tooltipTitle,
                                bodyColor: getChartColors().tooltipBody,
                            }
                        },
                        scales: {
                            x: {
                                ticks: { color: getChartColors().xTicks },
                                grid: { color: getChartColors().xGrid },
                            },
                            y: {
                                beginAtZero: true,
                                suggestedMax: 10,
                                ticks: { precision: 0, color: getChartColors().yTicks },
                                grid: { color: getChartColors().yGrid },
                            }
                        }
                    }
                });

                // Atualizar cores dos gráficos ao mudar tema
                const observer = new MutationObserver(() => {
                    const colors = getChartColors();

                    [graficoLinha, graficoEstoque, graficoVencidos].forEach(chart => {
                        chart.options.plugins.legend.labels.color = colors.legendColor;
                        chart.options.plugins.tooltip.backgroundColor = colors.tooltipBg;
                        chart.options.plugins.tooltip.titleColor = colors.tooltipTitle;
                        chart.options.plugins.tooltip.bodyColor = colors.tooltipBody;
                        chart.options.scales.x.ticks.color = colors.xTicks;
                        chart.options.scales.x.grid.color = colors.xGrid;
                        chart.options.scales.y.ticks.color = colors.yTicks;
                        chart.options.scales.y.grid.color = colors.yGrid;
                        chart.update();
                    });
                });

                observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });

                // Atualizar gráfico de estoque ao filtrar item
                document.getElementById('item').addEventListener('change', function () {
                    const itemSelecionado = this.value;
                    fetch(`/dashboard/estoque-dados?item=${encodeURIComponent(itemSelecionado)}`)
                        .then(response => response.json())
                        .then(data => {
                            graficoEstoque.data.datasets[0].data = data.entradas;
                            graficoEstoque.data.datasets[1].data = data.saidas;
                            graficoEstoque.update();
                        });
                });

                const limparFiltro = document.getElementById('limpar-filtro');
                if (limparFiltro) {
                    limparFiltro.addEventListener('click', () => {
                        document.getElementById('item').value = '';
                        document.getElementById('item').dispatchEvent(new Event('change'));
                    });
                }
            });
        </script>
    @endpush

</x-filament::page>
