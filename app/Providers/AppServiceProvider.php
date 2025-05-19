<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            \Illuminate\Auth\EloquentUserProvider::class,
            function ($app) {
                return new \App\Providers\TenantUserProvider(
                    $app['hash'],
                    config('auth.providers.tenant_users.model')
                );
            }
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Auth::provider('tenant', function ($app, array $config) {
            return new TenantUserProvider(
                $app->make(Hasher::class),
                $config['model'],
            );
        });
    }
}
