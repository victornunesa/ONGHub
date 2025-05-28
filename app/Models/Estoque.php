<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estoque extends Model
{
    protected $table = 'estoque';

    protected $fillable = [
        'ong_id', 'nome_item', 'quantidade',
        'quantidade_solicitada', 'data_atualizacao', 'unidade'
    ];

    protected $attributes = [
        'quantidade_solicitada' => 0,
    ];

    public function ong()
    {
        return $this->belongsTo(Ong::class);
    }
}
