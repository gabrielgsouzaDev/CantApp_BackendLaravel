<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserRole extends Pivot
{
    protected $table = 'tb_user_role';
    public $timestamps = true;

    protected $fillable = [
        'id_user',
        'id_role'
    ];
}
