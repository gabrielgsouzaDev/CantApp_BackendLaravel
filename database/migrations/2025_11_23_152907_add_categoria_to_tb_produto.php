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
        Schema::table('tb_produto', function (Blueprint $table) {
            $table->enum('categoria', ['Salgado', 'Doce', 'Bebida', 'Almoço'])->nullable()->after('nome');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_produto', function (Blueprint $table) {
            $table->dropColumn('categoria');
        });
    }
};
