<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionGroupsTable extends Migration
{
    public function up()
    {
        Schema::create('permission_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
        });
    }

    public function down()
    {
        Schema::dropIfExists('permission_groups');
    }
}
