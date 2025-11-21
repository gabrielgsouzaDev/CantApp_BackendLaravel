<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tb_produto', function (Blueprint $table) {
    $table->id('id_produto'); // INT UNSIGNED AUTO_INCREMENT
    $table->foreignId('id_cantina')
          ->constrained('tb_cantina', 'id_cantina') // <<--- CORRETO
          ->cascadeOnDelete();
    $table->string('nome', 150);
    $table->decimal('preco', 14, 2);
    $table->boolean('ativo')->default(true);
    $table->timestamps();
});

    }

    public function down(): void
    {
        Schema::dropIfExists('tb_produto');
    }
};
