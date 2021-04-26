<?php

namespace App;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;

class Produto extends Model
{
    use SearchableTrait, Searchable;

    protected $fillable = ['quantidade'];

    protected $searchable = [
        'columns' => [
            'products.nome' => 10,
            'products.details' => 5,
            'products.description' => 2,
        ],
    ];

    public function categorias()
    {
        return $this->belongsToMany('App\Categoria');
    }

    public function precoFormatado()
    {
        return money_format('R$%i', $this->preco / 100);
    }

    public function tambemPodeGostar($query)
    {
        return $query->inRandomOrder()->take(4);
    }

    public function paraMatrizPesquisavel()
    {
        $array = $this->toArray();
        $extraFields = [
            'categorias' => $this->categories->pluck('nome')->toArray(),
        ];
        return array_merge($array, $extraFields);
    }
}
