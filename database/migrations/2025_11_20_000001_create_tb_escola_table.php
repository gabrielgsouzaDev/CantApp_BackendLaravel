<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tb_escola', function (Blueprint $table) {
            $table->id('id_escola');
            $table->string('nome', 150);
            $table->string('cnpj', 20)->unique()->nullable();
            $table->foreignId('id_endereco')
                  ->nullable()
                  ->constrained('tb_endereco', 'id_endereco')
                  ->nullOnDelete();
            $table->foreignId('id_plano')
                  ->nullable()
                  ->constrained('tb_plano', 'id_plano')
                  ->nullOnDelete();
            $table->enum('status', ['ativa','inativa','pendente'])->default('pendente');
            $table->unsignedInteger('qtd_alunos')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_escola');
    }
};
