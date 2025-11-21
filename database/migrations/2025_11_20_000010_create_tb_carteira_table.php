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
        Schema::create('tb_carteira', function (Blueprint $table) {
    $table->id('id_carteira');
    $table->foreignId('id_user')->unique()->constrained('users')->cascadeOnDelete();
    $table->decimal('saldo', 14, 2)->default(0);
    $table->decimal('saldo_bloqueado', 14, 2)->default(0);
    $table->decimal('limite_recarregar', 14, 2)->nullable();
    $table->decimal('limite_maximo_saldo', 14, 2)->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_carteira');
    }
};
