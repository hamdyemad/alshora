<?php

namespace App\Http\Middleware\api;

use App\Services\LanguageService;
use Closure;
use Illuminate\Http\Request;

class LangMiddleware
{

    public function __construct(protected LanguageService $languageService) {
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
        $languages = $this->languageService->getAll();
        if($request->hasHeader('Accept-Language') && in_array($request->header('Accept-Language'), $languages->pluck('code')->toArray())) {
            app()->setLocale($request->header('Accept-Language'));
        }
        return $next($request);
    }
}
