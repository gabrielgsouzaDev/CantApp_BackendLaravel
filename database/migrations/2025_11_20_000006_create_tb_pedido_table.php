<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tb_pedido', function (Blueprint $table) {
    $table->id('id_pedido'); // BIGINT UNSIGNED
    $table->foreignId('id_cantina')
          ->constrained('tb_cantina', 'id_cantina') // CORRETO
          ->restrictOnDelete();
    $table->foreignId('id_comprador')
          ->constrained('users', 'id')
          ->restrictOnDelete();
    $table->foreignId('id_destinatario')
          ->constrained('users', 'id')
          ->restrictOnDelete();
    $table->decimal('valor_total', 14, 2);
    $table->enum('status', ['pendente','confirmado','cancelado','entregue'])->default('pendente');
    $table->timestamps();
});

    }

    public function down(): void
    {
        Schema::dropIfExists('tb_pedido');
    }
};