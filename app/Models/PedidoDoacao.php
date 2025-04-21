<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedidoDoacao extends Model
{
    protected $fillable = [
        'nome_solicitante',
        'email_solicitante',
        'telefone_solicitante',
        'descricao',
        'quantidade',
        'status',
        'data_pedido',
    ];

    public function ongs()
    {
        return $this->belongsToMany(Ong::class, 'pedido_doacao_ong_pivot');
    }

    public function doacoes()
    {
        return $this->belongsToMany(Doacao::class, 'pedido_doacao_doacao_pivot');
    }
}
