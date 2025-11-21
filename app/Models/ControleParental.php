<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControleParental extends Model
{
    use HasFactory;

    protected $table = 'tb_controle_parental';
    protected $primaryKey = 'id_controle';
    public $timestamps = true;

    protected $fillable = [
        'id_responsavel',
        'id_aluno',
        'ativo',
        'limite_diario',
        'dias_semana'
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'limite_diario' => 'decimal:2'
    ];

    public function responsavel()
    {
        return $this->belongsTo(User::class, 'id_responsavel', 'id');
    }

    public function aluno()
    {
        return $this->belongsTo(User::class, 'id_aluno', 'id');
    }

    public function produtos()
    {
        return $this->belongsToMany(
            Produto::class,
            'tb_controle_parental_produto',
            'id_controle',
            'id_produto'
        )->withPivot('permitido')->withTimestamps();
    }
}
