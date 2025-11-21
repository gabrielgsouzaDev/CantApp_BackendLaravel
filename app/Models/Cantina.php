<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        return $this->belongsTo(Escola::class, 'id_escola', 'id_escola');
    }

    public function produtos()
    {
        return $this->hasMany(Produto::class, 'id_cantina', 'id_cantina');
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'id_cantina', 'id_cantina');
    }

    public function usuarios()
    {
        return $this->hasMany(User::class, 'id_cantina', 'id_cantina')
                    ->whereHas('roles', function($q){
                        $q->where('nome_role','Cantina');
                    });
    }
}
