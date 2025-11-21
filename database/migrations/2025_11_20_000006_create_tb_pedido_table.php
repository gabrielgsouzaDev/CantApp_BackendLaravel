<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_pedido', function (Blueprint $table) {
            $table->bigIncrements('id_pedido');
            $table->unsignedBigInteger('id_cantina');
            $table->unsignedBigInteger('id_comprador');
            $table->unsignedBigInteger('id_destinatario');
            $table->decimal('valor_total', 14, 2);
            $table->enum('status', ['pendente','confirmado','cancelado','entregue'])->default('pendente');
            $table->timestamps();

            $table->foreign('id_cantina')
                ->references('id_cantina')
                ->on('tb_cantina');
            $table->foreign('id_comprador')
                ->references('id')
                ->on('users');
            $table->foreign('id_destinatario')
                ->references('id')
                ->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_pedido');
    }
};
