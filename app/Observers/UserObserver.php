<?php

namespace App\Observers;

use App\Actions\ActivityLog\CreateActivityLog;
use App\Enums\EventEnum;
use App\Models\User;

class UserObserver
{
    private CreateActivityLog $action;

    public function __construct()
    {
        $this->action = new CreateActivityLog();
    }

    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $this->logActivity($user, EventEnum::Created, 201);
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        $this->logActivity($user, EventEnum::Updated, 200);
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        $this->logActivity($user, EventEnum::Deleted, 200);
    }

    /**
     * Log the activity for user events.
     */
    private function logActivity(User $user, EventEnum $event, int $statusCode): void
    {
        $data = [
            'model' => User::class,
            'model_id' => $user->id,
            'event' => $event,
            'original' => json_encode($user->getOriginal()),
            'changes' => json_encode($user->getChanges()),
            'status_code' => $statusCode,
        ];

        $this->action->execute($data);
    }
}
