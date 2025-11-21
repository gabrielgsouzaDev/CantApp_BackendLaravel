<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_item_pedido', function (Blueprint $table) {
            $table->bigIncrements('id_item');
            $table->unsignedBigInteger('id_pedido');
            $table->unsignedBigInteger('id_produto');
            $table->integer('quantidade')->default(1);
            $table->decimal('preco_unitario', 14, 2);
            $table->timestamps();

            $table->foreign('id_pedido')
                ->references('id_pedido')
                ->on('tb_pedido')
                ->onDelete('cascade');

            $table->foreign('id_produto')
                ->references('id_produto')
                ->on('tb_produto');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_item_pedido');
    }
};
