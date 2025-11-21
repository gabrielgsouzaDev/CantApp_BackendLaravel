<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_cantina', function (Blueprint $table) {
            $table->bigIncrements('id_cantina');
            $table->string('nome', 150);
            $table->unsignedBigInteger('id_escola');
            $table->time('hr_abertura')->nullable();
            $table->time('hr_fechamento')->nullable();
            $table->timestamps();

            $table->foreign('id_escola')
                ->references('id_escola')
                ->on('tb_escola')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_cantina');
    }
};
