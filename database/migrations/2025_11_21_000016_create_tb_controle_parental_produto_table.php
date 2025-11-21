<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_controle_parental_produto', function (Blueprint $table) {
            $table->bigIncrements('id_controle_produto');
            $table->unsignedBigInteger('id_controle');
            $table->unsignedBigInteger('id_produto');
            $table->boolean('permitido')->default(1);
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['id_controle','id_produto']);

            $table->foreign('id_controle')
                ->references('id_controle')
                ->on('tb_controle_parental')
                ->onDelete('cascade');

            $table->foreign('id_produto')
                ->references('id_produto')
                ->on('tb_produto')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_controle_parental_produto');
    }
};
