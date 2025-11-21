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
        // Tabela de usuários
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 100);
            $table->string('email', 150)->unique();
            $table->string('senha_hash', 255);
            $table->unsignedBigInteger('id_escola')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();

            // Chave estrangeira para escola
            $table->foreign('id_escola')
                  ->references('id_escola')
                  ->on('tb_escola')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
        });

        // Tabela de tokens de reset de senha
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // **Removida criação duplicada de sessions**
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
        // sessions não é mais dropada aqui, Laravel cuida dela
    }
};
