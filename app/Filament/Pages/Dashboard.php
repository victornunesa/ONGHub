<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\IntencaoDoacao;
use App\Models\PedidoDoacao;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static string $view = 'filament.pages.dashboard';

    protected static ?string $title = 'Novo';
    protected static ?string $navigationLabel = 'Início';

    // Recebe o Request para captar filtro do item
    public function getViewData(): array
    {
        $ong = Auth::user()->ong;

        $intencoesRecentes = IntencaoDoacao::where('status', 'Registrada')
            ->where('ong_desejada', $ong->id)
            ->latest()
            ->take(3)
            ->get();

        $totalIntencoes = IntencaoDoacao::where('status', 'Registrada')
            ->where('ong_desejada', $ong->id)
            ->count();

        $pedidosRecentes = PedidoDoacao::where('status', 'Registrada')
            ->latest()
            ->take(3)
            ->get();

        $totalPedidos = PedidoDoacao::where('status', 'Registrada')->count();

        // Intenções por mês
        $intencoesPorMes = IntencaoDoacao::selectRaw("DATE_FORMAT(data_pedido, '%Y-%m') as mes, COUNT(*) as total")
            ->whereIn('status', ['Registrada', 'Recebida'])
            ->where('ong_desejada', $ong->id) // mantém filtro ONG
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('total', 'mes')
            ->toArray();

        // Pedidos por mês
        $pedidosPorMes = PedidoDoacao::selectRaw("DATE_FORMAT(data_pedido, '%Y-%m') as mes, COUNT(*) as total")
            ->whereIn('status', ['Registrada', 'Recebida'])
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('total', 'mes')
            ->toArray();

        // Últimos 6 meses
        $meses = [];
        $intencoesTotais = [];
        $pedidosTotais = [];

        for ($i = 5; $i >= 0; $i--) {
            $mes = Carbon::now()->subMonths($i)->format('Y-m');
            $meses[] = Carbon::createFromFormat('Y-m', $mes)->format('M/Y');
            $intencoesTotais[] = $intencoesPorMes[$mes] ?? 0;
            $pedidosTotais[] = $pedidosPorMes[$mes] ?? 0;
        }

        // --- FILTRO POR ITEM PARA ESTOQUE ---
        $itemSelecionado = request()->query('item', null);

        $mesesEstoqueFormatados = collect(range(0, 5))->map(function ($i) {
            return Carbon::now()->subMonths($i)->translatedFormat('M/Y');
        })->reverse()->values();

        $mesesEstoqueChaves = collect(range(0, 5))->map(function ($i) {
            return Carbon::now()->subMonths($i)->format('Y-m');
        })->reverse()->values();

        $mesesEstoqueFormatadosJS = collect($mesesEstoqueChaves)->map(function ($mes) {
            return Carbon::createFromFormat('Y-m', $mes)->translatedFormat('M/Y');
        })->values();

        $query = DB::table('doacao')
            ->select(
                DB::raw("DATE_FORMAT(data_doacao, '%Y-%m') as mes"),
                'status',
                DB::raw('COUNT(*) as total')
            )
            ->whereIn('status', ['Entrada', 'Saida']) // note o acento em Saída
            ->whereBetween('data_doacao', [
                Carbon::now()->subMonths(5)->startOfMonth(),
                Carbon::now()->endOfMonth()
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

        // Lista de itens para filtro
        $itens = DB::table('doacao')
            ->select('descricao')
            ->distinct()
            ->orderBy('descricao')
            ->pluck('descricao');

        return [
            'intencoesRecentes' => $intencoesRecentes,
            'totalIntencoes' => $totalIntencoes,
            'pedidosRecentes' => $pedidosRecentes,
            'totalPedidos' => $totalPedidos,

            // Dados para gráficos
            'graficoMeses' => $meses,
            'graficoIntencoesTotais' => $intencoesTotais,
            'graficoPedidosTotais' => $pedidosTotais,

            'mesesEstoque' => $mesesEstoqueFormatadosJS,
            'chavesMesesEstoque' => $mesesEstoqueChaves,
            'estoqueEntradas' => $entradas,
            'estoqueSaidas' => $saidas,

            'itens' => $itens,
            'itemSelecionado' => $itemSelecionado,
        ];
    }
}
