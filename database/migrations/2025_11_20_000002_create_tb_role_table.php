<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    public function up()
    {
        Schema::create('tb_role', function (Blueprint $table) {
            $table->id('id_role');
            $table->string('nome_role')->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tb_role');
    }
}
