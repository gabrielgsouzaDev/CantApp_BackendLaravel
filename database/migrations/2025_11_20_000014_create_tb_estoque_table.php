<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_estoque', function (Blueprint $table) {
            $table->bigIncrements('id_estoque');
            $table->unsignedBigInteger('id_produto');
            $table->integer('quantidade')->default(0);
            $table->timestamps();

            $table->foreign('id_produto')
                ->references('id_produto')
                ->on('tb_produto')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_estoque');
    }
};
