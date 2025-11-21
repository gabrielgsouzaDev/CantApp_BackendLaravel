<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
     Schema::create('tb_estoque', function (Blueprint $table) {
    $table->id('id_estoque'); // INT UNSIGNED
    $table->foreignId('id_produto')->constrained('tb_produto', 'id_produto')
          ->cascadeOnDelete();
    $table->integer('quantidade')->default(0);
    $table->timestamps();
});



    }

    public function down(): void
    {
        Schema::dropIfExists('tb_estoque');
    }
};
