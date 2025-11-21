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
        Schema::create('tb_controle_parental', function (Blueprint $table) {
    $table->id('id_controle');
    $table->foreignId('id_responsavel')->constrained('users')->cascadeOnDelete();
    $table->foreignId('id_aluno')->constrained('users')->cascadeOnDelete();
    $table->boolean('ativo')->default(true);
    $table->decimal('limite_diario', 14, 2)->nullable();
    $table->string('dias_semana', 50)->nullable();
    $table->timestamps();
    $table->unique('id_aluno');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_controle_parental');
    }
};
