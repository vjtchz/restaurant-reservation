<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
  /**
   * The root template that's loaded on the first page visit.
   *
   * @see https://inertiajs.com/server-side-setup#root-template
   *
   * @var string
   */
  protected $rootView = 'app';

  /**
   * Determines the current asset version.
   *
   * @see https://inertiajs.com/asset-versioning
   */
  public function version(Request $request): ?string
  {
    return parent::version($request);
  }

  /**
   * Define the props that are shared by default.
   *
   * @see https://inertiajs.com/shared-data
   *
   * @return array<string, mixed>
   */
  public function share(Request $request): array
  {
    return [
      ...parent::share($request),
      'name' => config('app.name'),
      'auth' => [
        'user' => $request->user(),
      ],
      'flash' => [
        'success' => $request->session()->get('success'),
        'error' => $request->session()->get('error'),
      ],
      'i18n' => [
        'reservations' => [
          'ui' => $this->normalizeVueI18nTokens(Lang::get('reservations.ui')),
        ],
      ],
      'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
    ];
  }

  /**
   * Convert Laravel-style :tokens to vue-i18n {tokens}.
   *
   * @param mixed $value
   * @return mixed
   */
  private function normalizeVueI18nTokens(mixed $value): mixed
  {
    if (is_array($value)) {
      return array_map(fn ($item) => $this->normalizeVueI18nTokens($item), $value);
    }

    if (is_string($value)) {
      return preg_replace('/\:([A-Za-z0-9_]+)/', '{$1}', $value);
    }

    return $value;
  }
}
