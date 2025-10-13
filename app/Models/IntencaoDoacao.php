<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntencaoDoacao extends Model
{
    use HasFactory;

    protected $table = 'intencao_doacao';

    protected $fillable = [
        'nome_solicitante', 'email_solicitante', 'telefone_solicitante',
        'ong_desejada', 'descricao', 'tipo', 'quantidade', 'unidade',
        'status', 'data_pedido'
    ];

    public function ong()
    {
        return $this->belongsTo(Ong::class, 'ong_desejada');
    }

    public function doacoes()
    {
        return $this->hasMany(Doacao::class, 'intencao_id');
    }

    public function getQuantidadeRecebidaAttribute(): string
    {
        $totalDoado = $this->doacoes()->sum('quantidade');

        return $totalDoado;
    }
}
