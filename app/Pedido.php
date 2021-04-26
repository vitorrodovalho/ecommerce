<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = [
        'usuario_id', 'email', 'nome', 'endereco', 'cidade',
        'estado', 'cep', 'telefone', 'nome_cartao', 'desconto', 'codigo_desconto', 'subtotal', 'total', 'gateway_pagamento', 'erro',
    ];

    public function usuario()
    {
        return $this->belongsTo('App\User');
    }

    public function produtos()
    {
        // Relacionamento muitos para muitos com Produtos
        return $this->belongsToMany('App\Produto')->withPivot('quantidade');
    }
}
