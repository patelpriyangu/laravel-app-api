<?php

namespace App\Http\Middleware;

use Closure;
use App\Traits\ApiResponseTrait;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    use ApiResponseTrait;
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return route('login');
        }
    }

    /**
     * handle
     *
     * @param mixed $request
     * @param Closure $next
     * @param mixed ...$guards
     * @return void
     */
    public function handle($request, Closure $next, ...$guards)
    {
        try {
            $this->authenticate($request, $guards);

            return $next($request);
        } catch (\Throwable $er) {
            return $this->responseFailJson(__('messages.permission_denied'), null, Response::HTTP_FORBIDDEN);
        }
    }
}
