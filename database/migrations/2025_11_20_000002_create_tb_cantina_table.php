<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
Schema::create('tb_cantina', function (Blueprint $table) {
    $table->id('id_cantina'); // chave primária
    $table->string('nome', 150); // nome da cantina
    $table->foreignId('id_escola') // FK para escola
          ->constrained('tb_escola', 'id_escola')
          ->cascadeOnDelete();
    $table->time('hr_abertura')->nullable(); // horário de abertura
    $table->time('hr_fechamento')->nullable(); // horário de fechamento
    $table->timestamps();
});


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_cantina');
    }
};
