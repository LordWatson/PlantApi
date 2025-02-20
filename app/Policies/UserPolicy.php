<?php

namespace App\Policies;

use App\Actions\ActivityLog\CreateActivityLog;
use App\Enums\EventEnum;
use App\Enums\RolesEnum;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    private int $accessLevel;
    private CreateActivityLog $activityLog;

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        // get the admin role
        $role = Role::where('name', RolesEnum::Admin)
            ->select('level')
            ->first();

        // set the required access level for this policy
        $this->accessLevel = $role->level;

        $this->activityLog = new CreateActivityLog();
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if(!$user->role->level >= $this->accessLevel){
            // log the 403
            $data = [
                'model' => User::class,
                'model_id' => $user->id,
                'user_id' => auth()->id() ?? null,
                'event' => EventEnum::Read,
                'message' => 'Access Denied',
            ];
            $this->activityLog->execute($data);

            return false;
        }

        return $user->role->level >= $this->accessLevel;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        //
    }
}
