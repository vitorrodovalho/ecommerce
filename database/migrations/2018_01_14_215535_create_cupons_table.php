<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCuponsTable extends Migration
{
    public function up()
    {
        Schema::create('cupons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo')->unique();
            $table->string('tipo');
            $table->integer('valor')->nullable();
            $table->integer('desconto')->nullable();
            //$table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cupons');
    }
}
