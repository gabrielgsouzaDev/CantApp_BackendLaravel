<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// CRÍTICO R51: Adicionar todas as Models relacionadas que estão faltando
use App\Models\Escola; 
use App\Models\Produto; 
use App\Models\Pedido; 
use App\Models\User; 
// Dependendo da sua estrutura, você pode precisar importar também a Model Role
// se ela não estiver na mesma pasta 'Models' que User.
use App\Models\Role; 

class Cantina extends Model
{
    use HasFactory;

    protected $table = 'tb_cantina';
    protected $primaryKey = 'id_cantina';
    public $timestamps = true;

    protected $fillable = [
        'nome',
        'id_escola',
        'hr_abertura',
        'hr_fechamento'
    ];

    // Relações
    public function escola()
    {
        // Agora o Laravel consegue resolver Escola::class
        return $this->belongsTo(Escola::class, 'id_escola', 'id_escola');
    }

    public function produtos()
    {
        // Agora o Laravel consegue resolver Produto::class
        return $this->hasMany(Produto::class, 'id_cantina', 'id_cantina');
    }

    public function pedidos()
    {
        // Agora o Laravel consegue resolver Pedido::class
        return $this->hasMany(Pedido::class, 'id_cantina', 'id_cantina');
    }

    public function usuarios()
    {
        return $this->hasMany(User::class, 'id_cantina', 'id_cantina')
            // O filtro 'Cantina' é o correto, conforme confirmado.
            ->whereHas('roles', function($q){
                // Agora o Laravel consegue resolver o relacionamento 'roles'
                $q->where('nome_role','Cantina'); 
            });
    }
}