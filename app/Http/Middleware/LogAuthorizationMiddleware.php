<?php

namespace App\Http\Middleware;

use App\Actions\ActivityLog\CreateActivityLog;
use App\Enums\EventEnum;
use Symfony\Component\HttpFoundation\Response;

class LogAuthorizationMiddleware
{
    public function handle($request, \Closure $next)
    {
        try {
            return $next($request);
        } catch (\Illuminate\Auth\Access\AuthorizationException $exception) {
            // log the 403 Forbidden exception
            (new CreateActivityLog())->execute([
                'user_id' => auth()->id(),
                'message' => 'Access Denied: ' . $exception->getMessage(),
                'status_code' => Response::HTTP_FORBIDDEN,
            ]);

            // re-throw the exception to continue default handling
            throw $exception;
        }
    }
}
