<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'users';

    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'data_nascimento',
        'senha_hash',
        'id_escola',
        'ativo',
    ];

    protected $hidden = [
        'senha_hash',
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'data_nascimento' => 'date',
    ];

    // RELACIONAMENTOS

    // Roles do usuário
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'tb_user_role', 'id_user', 'id_role');
    }

    // Escola do usuário (se for gestor ou aluno)
    public function escola()
    {
        return $this->belongsTo(Escola::class, 'id_escola', 'id_escola');
    }

    // Cantina do usuário (se for cantineiro)
    public function cantina()
    {
        return $this->hasOne(Cantina::class, 'id_cantina', 'id_cantina');
    }

    // Relacionamentos com Controle Parental e Dependentes
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

public function controleParentalAluno()
{
    return $this->hasOne(ControleParental::class, 'id_aluno', 'id');
}

public function controleParentalResponsavel()
{
    return $this->hasMany(ControleParental::class, 'id_responsavel', 'id');
}


    // Carteira financeira
    public function carteira()
    {
        return $this->hasOne(Carteira::class, 'id_user', 'id');
    }

    // CHECK ROLE
    public function hasRole(string $roleName): bool
    {
        return $this->roles()->where('nome_role', $roleName)->exists();
    }
}
