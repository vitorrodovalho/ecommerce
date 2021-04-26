<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriaProdutoTable extends Migration
{
    public function up()
    {
        Schema::create('categoria_produto', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('produto_id')->unsigned()->nullable();
            $table->foreign('produto_id')->references('id')
                  ->on('produtos')->onDelete('cascade');

            $table->integer('categoria_id')->unsigned()->nullable();
            $table->foreign('categoria_id')->references('id')
                  ->on('categoria')->onDelete('cascade');

            //$table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('categoria_produto');
    }
}
