<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ong extends Model
{
    protected $fillable = [
        'nome',
        'cnpj',
        'email',
        'telefone',
        'endereco',
        'status',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function relatorios()
    {
        return $this->hasMany(Relatorio::class);
    }

    public function estoques()
    {
        return $this->hasMany(Estoque::class);
    }

    public function pedidosDoacao()
    {
        return $this->belongsToMany(PedidoDoacao::class, 'pedido_doacao_ong_pivot');
    }

    public function doacoesEnviadas()
    {
        return $this->hasMany(Doacao::class, 'ong_origem_id');
    }

    public function doacoesRecebidas()
    {
        return $this->hasMany(Doacao::class, 'ong_destino_id');
    }
}
