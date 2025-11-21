<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_plano', function (Blueprint $table) {
            $table->bigIncrements('id_plano');
            $table->string('nome', 50);
            $table->decimal('preco_mensal', 12, 2)->default(0);
            $table->unsignedInteger('qtd_max_alunos')->default(0);
            $table->unsignedInteger('qtd_max_cantinas')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_plano');
    }
};
