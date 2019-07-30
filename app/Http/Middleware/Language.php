<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
class Language
{
    public function __construct(Application $app, Request $request) {
        $this->app = $app;
        $this->request = $request;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $locale = $request->get('lang', '');

        if(!$locale){
            $locale = session('language', $this->app->getLocale());
        }
        $locales = $this->app->config->get('app.locales');
        
        if ( ! array_key_exists($locale, $locales)) {
            $locale = $this->app->config->get('app.fallback_locale');
        }
        $this->app->setLocale($locale);
        session(['language' => $locale]);

        return $next($request);
    }
}
