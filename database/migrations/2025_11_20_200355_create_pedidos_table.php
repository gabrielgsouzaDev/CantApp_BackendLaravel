<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::create('pedidos', function (Blueprint $table) {
        $table->id();
        
        $table->foreignId('aluno_id')->constrained('alunos')
              ->onDelete('cascade');

        $table->foreignId('responsavel_id')->nullable()->constrained('responsaveis')
              ->nullOnDelete();

        $table->enum('status', ['pendente', 'aprovado', 'cancelado'])
              ->default('pendente');

        $table->decimal('valor_total', 10, 2)->default(0);

        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('pedidos');
}

};
