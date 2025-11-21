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
Schema::create('tb_user_role', function (Blueprint $table) {
    $table->unsignedBigInteger('id_user');
    $table->unsignedBigInteger('id_role');
    $table->timestamp('assigned_at')->useCurrent();
    $table->primary(['id_user','id_role']);

    // FK
    $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
    $table->foreign('id_role')->references('id_role')->on('tb_role')->onDelete('restrict')->onUpdate('cascade');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_user_role');
    }
};
