<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_transacao', function (Blueprint $table) {
            $table->id('id_transacao');
            $table->foreignId('id_carteira')
                  ->nullable()
                  ->constrained('tb_carteira', 'id_carteira')
                  ->restrictOnDelete();
            $table->foreignId('id_user_autor')
                  ->nullable()
                  ->constrained('users', 'id')
                  ->nullOnDelete();
            $table->foreignId('id_aprovador')
                  ->nullable()
                  ->constrained('users', 'id')
                  ->nullOnDelete();
            $table->uuid('uuid')->unique();
            $table->enum('tipo',['PIX','Recarregar','PagamentoEscola','Debito','Repasse','Estorno']);
            $table->decimal('valor',14,2);
            $table->string('descricao',500)->nullable();
            $table->string('referencia')->nullable();
            $table->enum('status',['pendente','confirmada','rejeitada'])->default('pendente');
            $table->timestamps();
            $table->index(['id_carteira','status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_transacao');
    }
};
