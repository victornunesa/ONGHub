<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IntencaoDoacao extends Model
{
    protected $table = 'intencao_doacao';
    protected $fillable = [
        'nome_solicitante',
        'email_solicitante',
        'telefone_solicitante',
        'descricao',
        'tipo',
        'quantidade',
        'unidade',
        'status',
        'data_pedido'
    ];
}
