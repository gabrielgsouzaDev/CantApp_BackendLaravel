<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cantina extends Model
{
    protected $table = 'tb_cantina';
    protected $primaryKey = 'id_cantina';

    protected $fillable = [
        'id_escola',
        'nome',
        'hr_abertura',
        'hr_fechamento',
    ];

    protected $casts = [
        'id_escola' => 'integer',
        'hr_abertura' => 'string',
        'hr_fechamento' => 'string',
    ];

    public $timestamps = true;

    // RELACIONAMENTOS
    public function escola()
    {
        return $this->belongsTo(Escola::class, 'id_escola', 'id_escola');
    }

    public function produtos()
    {
        return $this->hasMany(Produto::class, 'id_cantina', 'id_cantina');
    }
}
