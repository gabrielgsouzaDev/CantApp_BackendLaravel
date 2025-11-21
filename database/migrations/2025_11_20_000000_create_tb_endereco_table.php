<?php
// exemplo: database/migrations/2025_11_20_000001_create_tb_endereco_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tb_endereco', function (Blueprint $table) {
            $table->id('id_endereco');
            $table->string('cep', 10);
            $table->string('logradouro');
            $table->string('numero', 20)->nullable();
            $table->string('complemento', 100)->nullable();
            $table->string('bairro', 100)->nullable();
            $table->string('cidade');
            $table->string('estado', 50);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_endereco');
    }
};
