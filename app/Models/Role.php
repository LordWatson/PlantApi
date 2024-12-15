<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * Role has many users (Optional, for reverse lookup)
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles');
    }
}
