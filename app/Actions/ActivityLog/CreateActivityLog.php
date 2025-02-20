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
        $data['user_id'] = auth()->id() ?? null;
        $data['ip_address'] = request()->ip();
        $data['user_agent'] = request()->userAgent() ?? null;
        $data['path'] = request()->path();
        $data['method'] = request()->method();

        $activityLog = ActivityLog::create($data);

        return $activityLog;
    }
}
