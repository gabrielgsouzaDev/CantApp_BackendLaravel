<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;
    use Illuminate\Support\Facades\DB; // Adicionado para usar DB::statement

    return new class extends Migration
    {
        public function up(): void
        {
            // O valor 'concluida' é adicionado para transações finalizadas.
            // Isso resolve o erro 'Data truncated' causado por tentar inserir 'CONCLUIDO'.
            DB::statement("
                ALTER TABLE tb_transacao 
                MODIFY status ENUM('pendente','confirmada','rejeitada','concluida') 
                NOT NULL DEFAULT 'pendente'
            ");
        }

        public function down(): void
        {
            // Reverte o ENUM para o estado original.
            DB::statement("
                ALTER TABLE tb_transacao 
                MODIFY status ENUM('pendente','confirmada','rejeitada') 
                NOT NULL DEFAULT 'pendente'
            ");
        }
    };