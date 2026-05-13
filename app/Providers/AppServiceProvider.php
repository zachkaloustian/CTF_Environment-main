<?php

namespace App\Providers;

use App\Models\ChallengeSolve;
use App\Observers\ChallengeSolveObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ChallengeSolve::observe(ChallengeSolveObserver::class);
    }
}
