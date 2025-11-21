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
        Schema::create('tb_controle_parental_produto', function (Blueprint $table) {
            $table->id('id_controle_produto');

            // FK para tb_controle_parental
            $table->unsignedBigInteger('id_controle');
            $table->foreign('id_controle')
                  ->references('id_controle')
                  ->on('tb_controle_parental')
                  ->cascadeOnDelete();

            // FK para produtos (nome da PK correto)
            $table->unsignedBigInteger('id_produto');
            $table->foreign('id_produto')
                  ->references('id_produto') // corrigido
                  ->on('tb_produto')
                  ->cascadeOnDelete();

            $table->boolean('permitido')->default(true);
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['id_controle','id_produto']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_controle_parental_produto');
    }
};
