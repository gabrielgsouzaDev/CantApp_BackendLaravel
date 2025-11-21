<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_role', function (Blueprint $table) {
            $table->bigIncrements('id_role');
            $table->string('nome_role', 50);
            $table->string('descricao', 255)->nullable();
            $table->timestamps();

            $table->unique('nome_role');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_role');
    }
};
