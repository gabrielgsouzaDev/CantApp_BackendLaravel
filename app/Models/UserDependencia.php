<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserDependencia extends Pivot
{
    protected $table = 'tb_user_dependencia';
    protected $primaryKey = null; // tabela pivot não precisa de PK
    public $incrementing = false;
    public $timestamps = false;
}
