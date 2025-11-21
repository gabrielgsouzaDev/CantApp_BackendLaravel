<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_escola', function (Blueprint $table) {
            $table->bigIncrements('id_escola');
            $table->string('nome', 150);
            $table->string('cnpj', 20)->nullable();
            $table->unsignedBigInteger('id_endereco')->nullable();
            $table->unsignedBigInteger('id_plano')->nullable();
            $table->enum('status', ['ativa', 'inativa', 'pendente'])->default('pendente');
            $table->unsignedInteger('qtd_alunos')->default(0);
            $table->timestamps();

            $table->unique('cnpj');
            $table->foreign('id_endereco')
                ->references('id_endereco')
                ->on('tb_endereco')
                ->onDelete('set null');

            $table->foreign('id_plano')
                ->references('id_plano')
                ->on('tb_plano')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_escola');
    }
};
