<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoriaProduto extends Model
{
    protected $table = 'categoria_produto';
    protected $fillable = ['produto_id', 'categoria_id'];
}
