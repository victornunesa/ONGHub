<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doacao extends Model
{
    protected $table = 'doacao';

    protected $fillable = [
        'intencao_id',
        'pedido_id',
        'nome_doador',
        'email_doador',
        'telefone_doador',
        'descricao',
        'quantidade',
        'unidade',
        'data_doacao',
        'status'
    ];

    public function intencao()
    {
        return $this->belongsTo(IntencaoDoacao::class, 'intencao_id');
    }

    public function pedido()
    {
        return $this->belongsTo(PedidoDoacao::class, 'pedido_id');
    }
}