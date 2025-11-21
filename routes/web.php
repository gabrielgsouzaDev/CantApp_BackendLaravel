<?php

use Illuminate\Support\Facades\Route;

Route::get('/test-db', function() {
    try {
        DB::connection()->getPdo();
        return 'Conexão com banco OK!';
    } catch (\Exception $e) {
        return 'Erro na conexão: ' . $e->getMessage();
    }
});

Route::get('/teste', function () {
    return \App\Models\User::all();
});
