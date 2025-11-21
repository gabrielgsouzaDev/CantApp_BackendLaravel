<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Escola extends Model
{
    protected $table = 'tb_escola';
    protected $primaryKey = 'id_escola';

    protected $fillable = [
        'nome',
        'cnpj',
        'id_endereco',
        'id_plano',
        'status',
        'qtd_alunos',
    ];

    protected $casts = [
        'qtd_alunos' => 'integer',
    ];

    public $timestamps = true;

    // RELACIONAMENTOS
    public function endereco()
    {
        return $this->belongsTo(Endereco::class, 'id_endereco', 'id_endereco');
    }

    public function plano()
    {
        return $this->belongsTo(Plano::class, 'id_plano', 'id_plano');
    }

    public function usuarios()
    {
        return $this->hasMany(User::class, 'id_escola', 'id_escola');
    }

    public function cantinas()
    {
        return $this->hasMany(Cantina::class, 'id_escola', 'id_escola');
    }
}
