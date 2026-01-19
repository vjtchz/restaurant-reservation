<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
  /**
   * Handle an incoming request.
   *
   * @param Request $request
   * @param Closure $next
   * @return mixed
   */
  public function handle(Request $request, Closure $next)
  {
    $locales = config('app.locales', [config('app.locale')]);
    $locale = $request->session()->get('locale', config('app.locale'));

    if (! in_array($locale, $locales, true)) {
      $locale = config('app.locale');
    }

    App::setLocale($locale);

    return $next($request);
  }
}
