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
        'status',
        'ong_destino_id', // Obrigatório para doações recebidas
        'ong_origem_id',
        'data_validade'
    ];

    protected $casts = [
        'data_validade' => 'date',
    ];

    public function intencao()
    {
        return $this->belongsTo(IntencaoDoacao::class, 'intencao_id');
    }

    public function pedido()
    {
        return $this->belongsTo(PedidoDoacao::class, 'pedido_id');
    }

    public function ongDestino()
    {
        return $this->belongsTo(Ong::class, 'ong_destino_id');
    }

    public static function rules()
    {
        return [
            'data_validade' => 'required|date|after_or_equal:today',
        ];
    }
}