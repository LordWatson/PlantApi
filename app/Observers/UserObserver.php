<?php

namespace App\Observers;

use App\Actions\ActivityLog\CreateActivityLog;
use App\Actions\User\CreateUserAction;
use App\Enums\EventEnum;
use App\Models\ActivityLog;
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
        // notify

        // log
        $data = [
            'model' => User::class,
            'model_id' => $user->id,
            'user_id' => auth()->id() ?? null,
            'event' => EventEnum::Created,
            'original' => json_encode($user->getAttributes()),
            'changes' => json_encode($user->getChanges()),
        ];
        $this->action->execute($data);
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        // notify

        // log
        $data = [
            'model' => User::class,
            'model_id' => $user->id,
            'user_id' => auth()->id() ?? null,
            'event' => EventEnum::Updated,
            'original' => json_encode($user->getOriginal()),
            'changes' => json_encode($user->getChanges()),
        ];
        $this->action->execute($data);
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        $data = [
            'model' => User::class,
            'model_id' => $user->id,
            'user_id' => auth()->id() ?? null,
            'event' => EventEnum::Deleted,
            'original' => json_encode($user->getOriginal()),
            'changes' => json_encode($user->getChanges()),
        ];
        $this->action->execute($data);
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
