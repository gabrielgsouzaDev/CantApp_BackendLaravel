<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Grupo 1
        $this->call([
            \Database\Seeders\CacheSeeder::class,
            \Database\Seeders\JobSeeder::class,
            \Database\Seeders\FailedJobSeeder::class,
            \Database\Seeders\JobBatchSeeder::class,
            \Database\Seeders\MigrationSeeder::class,
        ]);

        // Grupo 2
        $this->call([
            \Database\Seeders\EnderecoSeeder::class,
            \Database\Seeders\PlanoSeeder::class,
            \Database\Seeders\EscolaSeeder::class,
            \Database\Seeders\CantinaSeeder::class,
            \Database\Seeders\ProdutoSeeder::class,
            \Database\Seeders\UserSeeder::class,
        ]);

        // Grupo 3
        $this->call([
            \Database\Seeders\ControleParentalSeeder::class,
            \Database\Seeders\ControleParentalProdutoSeeder::class,
            \Database\Seeders\UserDependenciaSeeder::class,
            \Database\Seeders\EstoqueSeeder::class,
            \Database\Seeders\PedidoSeeder::class,
            \Database\Seeders\ItemPedidoSeeder::class,
            \Database\Seeders\TransacaoSeeder::class,
        ]);

        // Roles e UserRole
        $this->call([
            \Database\Seeders\RoleSeeder::class,
            \Database\Seeders\UserRoleSeeder::class,
        ]);
    }
}
