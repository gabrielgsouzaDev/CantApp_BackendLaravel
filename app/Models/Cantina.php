<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// CRÍTICO: Modelos relacionados devem ser importados para funcionar, mesmo que não seja a causa do erro 500 atual.
use App\Models\User;
use App\Models\Transacao;

class Carteira extends Model
{
    use HasFactory;

    // R7: Viola a convenção ('carteiras'), mas mantido para compatibilidade com o DB.
    protected $table = 'tb_carteira';
    
    // R7: Viola a convenção ('id'), mas mantido para compatibilidade com o DB.
    protected $primaryKey = 'id_carteira';
    public $timestamps = true;

    protected $fillable = [
        'id_user', 
        'saldo',
        'saldo_bloqueado',
        'limite_recarregar',
        'limite_maximo_saldo'
    ];

    // R6: Cuidado! O cast 'decimal' ainda usa float para leitura. 
    // Foi corrigido para o MVP usar aritmética de inteiros no Service.
    protected $casts = [
        'saldo' => 'decimal:2',
        'saldo_bloqueado' => 'decimal:2',
        'limite_recarregar' => 'decimal:2',
        'limite_maximo_saldo' => 'decimal:2'
    ];

    /**
     * R7: Relacionamento com Usuário.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    /**
     * R7: Relacionamento com Transações.
     */
    public function transacoes()
    {
        return $this->hasMany(Transacao::class, 'id_carteira', 'id_carteira');
    }
}