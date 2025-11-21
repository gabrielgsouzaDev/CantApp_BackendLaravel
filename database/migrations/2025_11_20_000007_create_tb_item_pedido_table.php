<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tb_item_pedido', function (Blueprint $table) {
    $table->id('id_item'); // BIGINT UNSIGNED
    $table->foreignId('id_pedido')->unsignedBigInteger()
          ->constrained('tb_pedido', 'id_pedido')
          ->cascadeOnDelete();
    $table->foreignId('id_produto')->constrained('tb_produto', 'id_produto')
          ->restrictOnDelete();
    $table->integer('quantidade')->default(1);
    $table->decimal('preco_unitario', 14, 2);
    $table->timestamps();
});

    }

    public function down(): void
    {
        Schema::dropIfExists('tb_item_pedido');
    }
};