<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_produto_favorito', function (Blueprint $table) {
            $table->id('id_favorito');

            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_produto');

            $table->timestamps();

            $table->foreign('id_user')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->foreign('id_produto')
                ->references('id_produto')->on('tb_produto')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_produto_favorito');
    }
};
