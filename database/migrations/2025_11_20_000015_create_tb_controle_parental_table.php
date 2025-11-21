<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_controle_parental', function (Blueprint $table) {
            $table->bigIncrements('id_controle');
            $table->unsignedBigInteger('id_responsavel');
            $table->unsignedBigInteger('id_aluno');
            $table->boolean('ativo')->default(1);
            $table->decimal('limite_diario', 14, 2)->nullable();
            $table->string('dias_semana', 50)->nullable();
            $table->timestamps();

            $table->unique('id_aluno');

            $table->foreign('id_responsavel')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('id_aluno')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_controle_parental');
    }
};
