<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ControleParental extends Model
{
    protected $table = 'tb_controle_parental';
    protected $primaryKey = 'id_controle';

    protected $fillable = [
        'id_responsavel',
        'id_aluno',
        'ativo',
        'limite_diario',
        'dias_semana',
    ];

    protected $casts = [
        'id_responsavel' => 'integer',
        'id_aluno' => 'integer',
        'ativo' => 'boolean',
        'limite_diario' => 'decimal:2',
        'dias_semana' => 'string',
    ];

    public $timestamps = true;

    // Relacionamentos
    public function responsavel(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_responsavel', 'id');
    }

    public function aluno(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_aluno', 'id');
    }

    public function produtos(): BelongsToMany
    {
        return $this->belongsToMany(
            Produto::class,
            'tb_controle_parental_produto',
            'id_controle',
            'id_produto'
        )->withPivot('permitido')
         ->withTimestamps();
    }
}
