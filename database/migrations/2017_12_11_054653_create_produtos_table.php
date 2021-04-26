<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdutosTable extends Migration
{
    public function up()
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome')->unique();
            $table->string('slug')->unique();
            $table->string('detalhes')->nullable();
            $table->integer('preco');
            $table->text('descricao');
            $table->text('images')->nullable();
            $table->string('imagem')->nullable();
            $table->unsignedInteger('quantidade')->default(10);
            $table->boolean('destaque')->default(false);
            //$table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('produtos');
    }
}
