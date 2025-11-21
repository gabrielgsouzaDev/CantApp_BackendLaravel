<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'tb_role';
    protected $primaryKey = 'id_role';
    public $timestamps = true;

    protected $fillable = [
        'nome_role'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'tb_user_role', 'id_role', 'id_user')->withTimestamps();
    }
}
