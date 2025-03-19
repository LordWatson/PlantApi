<?php

namespace App\Policies;

use App\Actions\ActivityLog\CreateActivityLog;
use App\Enums\EventEnum;
use App\Enums\RolesEnum;
use App\Models\Role;
use App\Models\User;

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
        return $this->hasAccess($user);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        return $this->canManageModel($user, $model);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        return $this->canManageModel($user, $model);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        return $this->canManageModel($user, $model);
    }

    /**
     * Check access level for the user.
     */
    private function hasAccess(User $user): bool
    {
        if ($user->role->level < $this->accessLevel) {
            $this->logAccessDenied($user);
            return false;
        }

        return true;
    }

    /**
     * Check if the user has access or is the owner of the model.
     */
    private function canManageModel(User $user, User $model): bool
    {
        return $this->hasAccess($user) || $user->id === $model->id;
    }

    /**
     * Log access denial.
     */
    private function logAccessDenied(User $user): void
    {
        $data = [
            'model' => User::class,
            'model_id' => $user->id,
            'user_id' => auth()->id() ?? null,
            'event' => EventEnum::Read,
            'message' => 'Access Denied',
        ];

        $this->activityLog->execute($data);
    }
}
