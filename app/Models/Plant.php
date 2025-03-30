<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plant extends Model
{
    // a type of plant can be owned by many users
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_plants')
            ->withPivot('last_watered', 'last_watered_unit');
    }
}
