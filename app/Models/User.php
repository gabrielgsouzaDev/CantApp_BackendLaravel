<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $table = 'users';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'data_nascimento',
        'senha_hash',
        'id_escola',
        'id_cantina',
        'ativo'
    ];

    protected $hidden = [
        'senha_hash',
        'remember_token',
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'data_nascimento' => 'date',
    ];

    /**
     * Get the password for authentication.
     * Isso permite que o Laravel use senha_hash no Auth::attempt()
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->senha_hash;
    }

    // Relação com roles (muitos-para-muitos)
    public function roles()
    {
        return $this->belongsToMany(
            Role::class,
            'tb_user_role',
            'id_user',
            'id_role'
        ); // remover withTimestamps() se a tabela pivô não tiver created_at/updated_at
    }

    // Relação com escola e cantina
    public function escola()
    {
        return $this->belongsTo(Escola::class, 'id_escola', 'id_escola');
    }

    public function cantina()
    {
        return $this->belongsTo(Cantina::class, 'id_cantina', 'id_cantina');
    }

    // Carteira do usuário
    public function carteira()
    {
        return $this->hasOne(Carteira::class, 'id_user', 'id');
    }

    // Transações feitas e aprovadas pelo usuário
    public function transacoesFeitas()
    {
        return $this->hasMany(Transacao::class, 'id_user_autor', 'id');
    }

    public function transacoesAprovadas()
    {
        return $this->hasMany(Transacao::class, 'id_aprovador', 'id');
    }

    // Dependentes e responsáveis
    public function dependentes()
    {
        return $this->belongsToMany(
            User::class,
            'tb_user_dependencia',
            'id_responsavel',
            'id_dependente'
        );
    }

    public function responsaveis()
    {
        return $this->belongsToMany(
            User::class,
            'tb_user_dependencia',
            'id_dependente',
            'id_responsavel'
        );
    }

    // Controle parental
    public function controleParentalComoResponsavel()
    {
        return $this->hasMany(ControleParental::class, 'id_responsavel', 'id');
    }

    public function controleParentalComoAluno()
    {
        return $this->hasOne(ControleParental::class, 'id_aluno', 'id');
    }

    // Pedidos feitos e recebidos
    public function pedidosFeitos()
    {
        return $this->hasMany(Pedido::class, 'id_comprador', 'id');
    }

    public function pedidosRecebidos()
    {
        return $this->hasMany(Pedido::class, 'id_destinatario', 'id');
    }
}
