<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePedidoProdutoTable extends Migration
{
    public function up()
    {
        Schema::create('pedido_produto', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pedido_id')->unsigned()->nullable();
            $table->foreign('pedido_id')->references('id')
                  ->on('pedidos')->onUpdate('cascade')->onDelete('set null');

            $table->integer('produto_id')->unsigned()->nullable();
            $table->foreign('produto_id')->references('id')
                ->on('produtos')->onUpdate('cascade')->onDelete('set null');

            $table->integer('quantidade')->unsigned();
            //$table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pedido_produto');
    }
}
