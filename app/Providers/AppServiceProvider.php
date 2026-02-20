<?php

namespace App\Providers;

use Illuminate\Support\Facades\Date;
use Illuminate\Support\ServiceProvider;
use App\Models\Cita;
use App\Policies\CitaPolicy;
use Illuminate\Support\Facades\Gate;

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
        // Registrar policy de Cita
        Gate::policy(Cita::class, CitaPolicy::class);
        Date::setLocale('es');
    }
}

