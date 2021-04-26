<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePedidosTable extends Migration
{
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('usuario_id')->unsigned()->nullable();
            $table->foreign('usuario_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');
            $table->string('email')->nullable();
            $table->string('nome')->nullable();
            $table->string('endereco')->nullable();
            $table->string('cidade')->nullable();
            $table->string('estado')->nullable();
            $table->string('cep')->nullable();
            $table->string('telefone')->nullable();
            $table->string('nome_cartao')->nullable();
            $table->integer('desconto')->default(0);
            $table->string('codigo_desconto')->nullable();
            $table->integer('subtotal');
            $table->integer('total');
            $table->string('gateway_pagamento')->default('stripe');
            $table->boolean('enviado')->default(false);
            $table->string('erro')->nullable();
            //$table->timestamps();
        });
    }

     public function down()
    {
        Schema::dropIfExists('pedidos');
    }
}
