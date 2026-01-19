<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use App\Events\ReservationCancelled;
use App\Events\ReservationCreated;
use App\Services\ReservationService;
use App\Services\ReservationServiceInterface;
use App\Listeners\SendReservationCancelledEmailListener;
use App\Listeners\SendReservationCreatedEmailListener;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            ReservationServiceInterface::class,
            ReservationService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        $this->configureEvents();
    }

    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null
        );
    }

    protected function configureEvents(): void
    {
        Event::listen(
            ReservationCreated::class,
            SendReservationCreatedEmailListener::class
        );

        Event::listen(
            ReservationCancelled::class,
            SendReservationCancelledEmailListener::class
        );
    }
}
