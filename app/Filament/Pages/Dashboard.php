<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\IntencaoDoacao;
use App\Models\PedidoDoacao;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static string $view = 'filament.pages.dashboard';

    protected static ?string $title = 'Novo'; // Altera o título da página
    protected static ?string $navigationLabel = 'Início';

    public function getViewData(): array
    {
        $ong = Auth::user()->ong;

        return [
            // Intenções filtradas por ONG
            'intencoesRecentes' => IntencaoDoacao::where('status', 'Registrada')
                ->where('ong_desejada', $ong->id)
                ->latest()
                ->take(3)
                ->get(),

            'totalIntencoes' => IntencaoDoacao::where('status', 'Registrada')
                ->where('ong_desejada', $ong->id)
                ->count(),

            // Pedidos de doação (sem filtro de ONG)
            'pedidosRecentes' => PedidoDoacao::where('status', 'Registrada')
                ->latest()
                ->take(3)
                ->get(),

            'totalPedidos' => PedidoDoacao::where('status', 'Registrada')->count(),
        ];
    }
}
