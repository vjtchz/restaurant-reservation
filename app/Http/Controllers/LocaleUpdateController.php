<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Update the active locale stored in session.
 */
class LocaleUpdateController extends Controller
{
  /**
   * Handle the incoming request.
   */
  public function __invoke(Request $request)
  {
    $locales = config('app.locales', [config('app.locale')]);
    $locale = $request->string('locale')->toString();

    if (! in_array($locale, $locales, true)) {
      abort(404);
    }

    $request->session()->put('locale', $locale);

    return back();
  }
}
