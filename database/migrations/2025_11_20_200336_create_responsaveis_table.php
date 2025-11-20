<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::create('responsaveis', function (Blueprint $table) {
        $table->id();
        $table->string('nome', 100);
        $table->string('email', 100)->unique();
        $table->string('senha_hash', 255);
        $table->string('telefone', 20)->nullable();
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('responsaveis');
}

};
