<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Estoque extends Model
{
    use HasFactory;

    protected $table = 'estoque';

    protected $fillable = [
        'ong_id', 'nome_item', 'quantidade',
        'quantidade_solicitada', 'data_atualizacao', 'unidade', 'data_validade'
    ];

    protected $casts = [
        'data_validade' => 'date',
    ];

    protected $attributes = [
        'quantidade_solicitada' => 0,
    ];

    protected static function booted()
    {
        static::addGlobalScope('ongOnly', function (Builder $builder) {
            $builder->where('ong_id', auth()->user()->ong->id);
        });
    }

    public function ong()
    {
        return $this->belongsTo(Ong::class);
    }

    public static function rules()
    {
        return [
            'data_validade' => 'required|date|after_or_equal:today',
        ];
    }

    public function adicionarQuantidade(int $valor): void
    {
        $this->quantidade += $valor;
        $this->data_atualizacao = now();
        $this->save();
    }

    public function removerQuantidade(int $valor): void
    {
        if ($valor > $this->quantidade) {
            throw new \InvalidArgumentException('Quantidade insuficiente em estoque');
        }

        $this->quantidade -= $valor;
        $this->data_atualizacao = now();
        $this->save();
    }

    public function calcularQuantidadeFinal(int $valor): int
    {
        return $this->quantidade + $valor;
    }
}
