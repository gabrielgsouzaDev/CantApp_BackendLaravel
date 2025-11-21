<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Escola
 * 
 * @property int $id_escola
 * @property string $nome
 * @property string|null $cnpj
 * @property int|null $id_endereco
 * @property int|null $id_plano
 * @property string $status
 * @property int $qtd_alunos
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Escola extends Model
{
    protected $table = 'tb_escola';
    protected $primaryKey = 'id_escola';

    // Preenchíveis via mass-assignment
    protected $fillable = [
        'nome',
        'cnpj',
        'id_endereco',
        'id_plano',
        'status',
        'qtd_alunos',
    ];

    // Campos escondidos em arrays/JSON
    protected $hidden = [
        // nenhum por enquanto
    ];

    // Casts úteis
    protected $casts = [
        'qtd_alunos' => 'integer',
    ];

    public $timestamps = true;

    // Relacionamentos
    public function endereco()
    {
        return $this->belongsTo(Endereco::class, 'id_endereco', 'id_endereco');
    }

    public function plano()
    {
        return $this->belongsTo(Plano::class, 'id_plano', 'id_plano');
    }

    public function alunos()
    {
        return $this->hasMany(Aluno::class, 'id_escola', 'id_escola');
    }

    public function cantinas()
    {
        return $this->hasMany(Cantina::class, 'id_escola', 'id_escola');
    }
}   