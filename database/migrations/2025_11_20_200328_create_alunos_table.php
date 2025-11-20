<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::create('alunos', function (Blueprint $table) {
        $table->id();
        $table->string('nome', 100);
        $table->string('email', 100)->unique();
        $table->string('senha_hash', 255);
        $table->date('data_nascimento')->nullable();
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('alunos');
}

};
