<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doacao extends Model
{
    protected $fillable = [
        'ong_origem_id',
        'ong_destino_id',
        'nome_doador',
        'email_doador',
        'telefone_doador',
        'descricao',
        'quantidade',
        'data_doacao',
        'status',
    ];

    public function origem()
    {
        return $this->belongsTo(Ong::class, 'ong_origem_id');
    }

    public function destino()
    {
        return $this->belongsTo(Ong::class, 'ong_destino_id');
    }

    public function pedidos()
    {
        return $this->belongsToMany(PedidoDoacao::class, 'pedido_doacao_doacao_pivot');
    }

    public function estoques()
    {
        return $this->belongsToMany(Estoque::class, 'doacao_estoque_pivot')
                    ->withPivot('quantidade');
    }
}
