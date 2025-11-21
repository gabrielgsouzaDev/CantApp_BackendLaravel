<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_produto', function (Blueprint $table) {
            $table->bigIncrements('id_produto');
            $table->unsignedBigInteger('id_cantina');
            $table->string('nome', 150);
            $table->decimal('preco', 14, 2);
            $table->boolean('ativo')->default(1);
            $table->timestamps();

            $table->foreign('id_cantina')
                ->references('id_cantina')
                ->on('tb_cantina')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_produto');
    }
};
