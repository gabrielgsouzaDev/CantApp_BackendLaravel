<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Endereco extends Model
{
    use HasFactory;

    protected $table = 'tb_endereco';
    protected $primaryKey = 'id_endereco';
    public $timestamps = true;

    protected $fillable = [
        'cep',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado'
    ];

    public function escolas()
    {
        return $this->hasMany(Escola::class, 'id_endereco', 'id_endereco');
    }
}
