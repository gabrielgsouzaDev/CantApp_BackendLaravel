<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            EnderecoSeeder::class,
            PlanoSeeder::class,
            EscolaSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            UserRoleSeeder::class,
            DependenciaSeeder::class,
            CantinaSeeder::class,
            ProdutoSeeder::class,
            EstoqueSeeder::class,
            ControleParentalSeeder::class,
            ControleParentalProdutoSeeder::class,
        ]);
    }
}
