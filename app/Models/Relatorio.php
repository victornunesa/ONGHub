<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Relatorio extends Model
{
    protected $fillable = [
        'ong_id',
        'tipo',
        'data_geracao',
    ];

    public function ong()
    {
        return $this->belongsTo(Ong::class);
    }
}
