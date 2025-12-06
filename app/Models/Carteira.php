<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carteira extends Model
{
    use HasFactory;

    // R7: Viola a convenção ('carteiras'), mas mantido para compatibilidade com o DB.
    protected $table = 'tb_carteira';
    
    // R7: Viola a convenção ('id'), mas mantido para compatibilidade com o DB.
    protected $primaryKey = 'id_carteira';
    public $timestamps = true;

    protected $fillable = [
        'id_user', // R7: Esta deve ser a Foreign Key (user_id)
        'saldo',
        'saldo_bloqueado',
        'limite_recarregar',
        'limite_maximo_saldo'
    ];

    // R6: Cuidado! O cast 'decimal' ainda usa float para leitura.
    protected $casts = [
        'saldo' => 'decimal:2',
        'saldo_bloqueado' => 'decimal:2',
        'limite_recarregar' => 'decimal:2',
        'limite_maximo_saldo' => 'decimal:2'
    ];

    /**
     * R7: Relacionamento com Usuário.
     * Necessário especificar as chaves estrangeira e local.
     */
    public function user()
    {
        // $this->belongsTo(Related, Foreign Key, Local Key)
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    /**
     * R7: Relacionamento com Transações.
     * Necessário especificar a chave estrangeira (FK) na tabela de transações.
     */
    public function transacoes()
    {
        // $this->hasMany(Related, Foreign Key on Transacoes Table, Local Key on Carteira Table)
        return $this->hasMany(Transacao::class, 'id_carteira', 'id_carteira');
    }
}