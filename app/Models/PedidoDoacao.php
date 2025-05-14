<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedidoDoacao extends Model
{
    protected $table = 'pedido_doacao';

    protected $casts = [
    'quantidade' => 'decimal:2',
    ];

    protected $fillable = [
        'nome_solicitante', 'email_solicitante', 'telefone_solicitante',
        'descricao', 'quantidade', 'status', 'data_pedido', 'tipo', 'unidade'
    ];

}
