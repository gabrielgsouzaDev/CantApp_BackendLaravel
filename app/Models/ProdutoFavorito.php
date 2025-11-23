<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdutoFavorito extends Model
{
    protected $table = 'tb_produto_favorito';
    protected $primaryKey = 'id_favorito';

    protected $fillable = [
        'id_user',
        'id_produto'
    ];

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'id_produto', 'id_produto');
    }
}
