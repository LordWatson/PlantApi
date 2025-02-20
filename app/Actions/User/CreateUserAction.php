<?php

namespace App\Actions\User;

use App\Enums\RolesEnum;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CreateUserAction
{
    /**
     * Create the action.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function execute(array $data) : User
    {
        $user = User::create($data);
        $user->assignRole(RolesEnum::User);

        return $user;
    }
}
