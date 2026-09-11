<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Locales the app can be switched to, keyed by locale code with their native display name.
     *
     * @var array<string, string>
     */
    public const array SUPPORTED = [
        'en' => 'English',
        'ur' => 'اردو',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->session()->get('locale');

        if (is_string($locale) && array_key_exists($locale, self::SUPPORTED)) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}
