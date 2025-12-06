<?php

namespace App\Http\Middleware\api;

use App\Models\User;
use App\Models\UserType;
use App\Services\LanguageService;
use App\Traits\Res;
use Closure;
use Illuminate\Http\Request;

class LawyerMiddleware
{
    use Res;

    public function __construct() {
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(auth()->user()->user_type_id == UserType::LAWYER_TYPE) {
            return $next($request);
        } else {
            return $this->sendRes(__('auth.unauthenticated'), false, [], [], 401);

        }
    }
}
