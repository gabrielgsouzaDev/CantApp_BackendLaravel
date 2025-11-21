<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carteira extends Model
{
    use HasFactory;

    protected $table = 'tb_carteira';
    protected $primaryKey = 'id_carteira';
    public $timestamps = true;

    protected $fillable = [
        'id_user',
        'saldo',
        'saldo_bloqueado',
        'limite_recarregar',
        'limite_maximo_saldo'
    ];

    protected $casts = [
        'saldo' => 'decimal:2',
        'saldo_bloqueado' => 'decimal:2',
        'limite_recarregar' => 'decimal:2',
        'limite_maximo_saldo' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function transacoes()
    {
        return $this->hasMany(Transacao::class, 'id_carteira', 'id_carteira');
    }
}
