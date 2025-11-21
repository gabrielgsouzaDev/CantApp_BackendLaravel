<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Cantina
 *
 * @property int $id_cantina
 * @property string $nome
 * @property int $id_escola
 * @property string|null $hr_abertura
 * @property string|null $hr_fechamento
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Cantina extends Model
{
    protected $table = 'tb_cantina';
    protected $primaryKey = 'id_cantina';

    protected $fillable = [
        'nome',
        'id_escola',
        'hr_abertura',
        'hr_fechamento',
    ];

    protected $hidden = [
        // nenhum por enquanto
    ];

    protected $casts = [
        'id_escola' => 'integer',
        'hr_abertura' => 'string',
        'hr_fechamento' => 'string',
    ];

    public $timestamps = true;

    // Relacionamentos
    public function escola()
    {
        return $this->belongsTo(Escola::class, 'id_escola', 'id_escola');
    }

    public function produtos()
    {
        return $this->hasMany(Produto::class, 'id_cantina', 'id_cantina');
    }
}
