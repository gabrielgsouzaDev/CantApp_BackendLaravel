<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plano extends Model
{
    use HasFactory;

    protected $table = 'tb_plano';
    protected $primaryKey = 'id_plano';
    public $timestamps = true;

    protected $fillable = [
        'nome',
        'preco_mensal',
        'qtd_max_alunos',
        'qtd_max_cantinas'
    ];

    public function escolas()
    {
        return $this->hasMany(Escola::class, 'id_plano', 'id_plano');
    }
}
