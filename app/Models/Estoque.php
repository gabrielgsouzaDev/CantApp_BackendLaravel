<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estoque extends Model
{
    use HasFactory;

    protected $table = 'tb_estoque';
    protected $primaryKey = 'id_estoque';
    public $timestamps = true;

    protected $fillable = [
        'id_produto',
        'quantidade'
    ];

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'id_produto', 'id_produto');
    }
}
