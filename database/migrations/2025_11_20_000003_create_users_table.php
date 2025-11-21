<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome', 100);
            $table->string('email', 150)->unique();
            $table->string('telefone', 20)->nullable();
            $table->date('data_nascimento')->nullable();
            $table->string('senha_hash', 255);
            $table->unsignedBigInteger('id_escola')->nullable();
            $table->unsignedBigInteger('id_cantina')->nullable();
            $table->boolean('ativo')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_escola')
                ->references('id_escola')
                ->on('tb_escola')
                ->onDelete('set null')
                ->onUpdate('cascade');

            $table->foreign('id_cantina', 'fk_user_cantina')
                ->references('id_cantina')
                ->on('tb_cantina')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
