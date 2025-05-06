<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ong extends Model
{
    use HasFactory;

    protected $table = 'ong';

    protected $fillable = [
        'nome',
        'cnpj',
        'email',
        'telefone',
        'endereco',
        'status'
    ];

    public function usuarios()
    {
        return $this->hasMany(User::class);
    }
}
