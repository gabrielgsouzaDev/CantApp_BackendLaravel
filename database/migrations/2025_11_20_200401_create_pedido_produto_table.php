<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::create('pedido_produto', function (Blueprint $table) {
        $table->id();

        $table->foreignId('pedido_id')->constrained('pedidos')
              ->onDelete('cascade');

        $table->foreignId('produto_id')->constrained('produtos')
              ->onDelete('cascade');

        $table->integer('quantidade')->default(1);

        $table->decimal('subtotal', 10, 2)->default(0);

        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('pedido_produto');
}

};
