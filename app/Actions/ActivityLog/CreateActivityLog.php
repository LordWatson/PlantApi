<?php

namespace App\Actions\ActivityLog;

use App\Models\ActivityLog;

class CreateActivityLog
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

    public function execute(array $data) : ActivityLog
    {
        $activityLog = ActivityLog::create($data);

        return $activityLog;
    }
}
