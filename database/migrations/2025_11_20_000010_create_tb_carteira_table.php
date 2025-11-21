<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_carteira', function (Blueprint $table) {
            $table->bigIncrements('id_carteira');
            $table->unsignedBigInteger('id_user');
            $table->decimal('saldo', 14, 2)->default(0.00);
            $table->decimal('saldo_bloqueado', 14, 2)->default(0.00);
            $table->decimal('limite_recarregar', 14, 2)->nullable();
            $table->decimal('limite_maximo_saldo', 14, 2)->nullable();
            $table->timestamps();

            $table->unique('id_user');

            $table->foreign('id_user')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_carteira');
    }
};
