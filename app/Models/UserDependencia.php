<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDependencia extends Model
{
    use HasFactory;

    protected $table = 'tb_user_dependencia';
    public $timestamps = true;
    public $incrementing = false;

    protected $primaryKey = ['id_responsavel','id_dependente'];
    protected $fillable = [
        'id_responsavel',
        'id_dependente'
    ];

    public function responsavel()
    {
        return $this->belongsTo(User::class, 'id_responsavel', 'id');
    }

    public function dependente()
    {
        return $this->belongsTo(User::class, 'id_dependente', 'id');
    }
}
