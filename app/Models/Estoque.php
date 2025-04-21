<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estoque extends Model
{
    protected $fillable = [
        'ong_id',
        'nome_item',
        'quantidade',
        'quantidade_solicitada',
        'data_atualizacao',
    ];

    public function ong()
    {
        return $this->belongsTo(Ong::class);
    }

    public function doacoes()
    {
        return $this->belongsToMany(Doacao::class, 'doacao_estoque_pivot')
                    ->withPivot('quantidade');
    }
}
