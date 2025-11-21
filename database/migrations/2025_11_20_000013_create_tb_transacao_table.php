<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_transacao', function (Blueprint $table) {
            $table->bigIncrements('id_transacao');
            $table->unsignedBigInteger('id_carteira')->nullable();
            $table->unsignedBigInteger('id_user_autor')->nullable();
            $table->unsignedBigInteger('id_aprovador')->nullable();
            $table->char('uuid', 36);
            $table->enum('tipo', ['PIX','Recarregar','PagamentoEscola','Debito','Repasse','Estorno']);
            $table->decimal('valor', 14, 2);
            $table->string('descricao', 500)->nullable();
            $table->string('referencia', 255)->nullable();
            $table->enum('status', ['pendente','confirmada','rejeitada'])->default('pendente');
            $table->timestamps();

            $table->unique('uuid');
            $table->index(['id_carteira','status']);
            $table->foreign('id_carteira')
                ->references('id_carteira')
                ->on('tb_carteira');
            $table->foreign('id_user_autor')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
            $table->foreign('id_aprovador')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_transacao');
    }
};
