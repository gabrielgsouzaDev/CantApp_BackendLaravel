<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_user_dependencia', function (Blueprint $table) {
            $table->unsignedBigInteger('id_responsavel');
            $table->unsignedBigInteger('id_dependente');
            $table->timestamp('created_at')->useCurrent();
            $table->primary(['id_responsavel', 'id_dependente']);

            $table->foreign('id_responsavel')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('id_dependente')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_user_dependencia');
    }
};
