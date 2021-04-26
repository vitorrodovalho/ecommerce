<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $guarded = [];
    protected $table = 'categoria';

    public function produtos()
    {
        // Relacionamento muitos para muitos com a Produtos
        return $this->belongsToMany('App\Produto');
    }
}
