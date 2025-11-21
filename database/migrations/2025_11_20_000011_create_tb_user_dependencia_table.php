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
        Schema::create('tb_user_dependencia', function (Blueprint $table) {
    $table->foreignId('id_responsavel')->constrained('users')->cascadeOnDelete();
    $table->foreignId('id_dependente')->constrained('users')->cascadeOnDelete();
    $table->timestamp('created_at')->useCurrent();
    $table->primary(['id_responsavel','id_dependente']);
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_user_dependencia');
    }
};
