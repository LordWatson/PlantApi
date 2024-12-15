<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts() : array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // user has one role through the pivot table
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles')
            ->withTimestamps()
            ->as('pivot')
            ->first();
    }

    // method to assign a role to a user
    public function assignRole($roleName)
    {
        $role = Role::where('name', $roleName)->firstOrFail();

        $this->roles()->attach($role);

        //return the user to allow method chaining if needed
        return $this;
    }

    // checks if a user is admin
    public function isAdmin()
    {
        foreach($this->roles()->get() as $role) {
            if ($role->name == 'Admin') {
                return true;
            }
        }

        return false;
    }
}
