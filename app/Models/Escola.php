<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Escola extends Model
{
    use HasFactory;

    protected $table = 'tb_escola';
    protected $primaryKey = 'id_escola';
    public $timestamps = true;

    protected $fillable = [
        'nome',
        'cnpj',
        'id_endereco',
        'id_plano',
        'status',
        'qtd_alunos'
    ];

    // Relações
    public function endereco()
    {
        return $this->belongsTo(Endereco::class, 'id_endereco', 'id_endereco');
    }

    public function plano()
    {
        return $this->belongsTo(Plano::class, 'id_plano', 'id_plano');
    }

    public function cantinas()
    {
        return $this->hasMany(Cantina::class, 'id_escola', 'id_escola');
    }

    public function alunos()
    {
        return $this->hasMany(User::class, 'id_escola', 'id_escola')
                    ->whereHas('roles', function($q){
                        $q->where('nome_role','Aluno');
                    });
    }
}
