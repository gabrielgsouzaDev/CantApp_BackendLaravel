<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControleParentalProduto extends Model
{
    use HasFactory;

    protected $table = 'tb_controle_parental_produto';
    protected $primaryKey = 'id_controle_parental_produto';
    public $timestamps = true;

    protected $fillable = [
        'id_controle',
        'id_produto',
        'permitido'
    ];

    // RELAÇÕES
    public function controle()
    {
        return $this->belongsTo(ControleParental::class, 'id_controle', 'id_controle_parental');
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'id_produto', 'id_produto');
    }
}
