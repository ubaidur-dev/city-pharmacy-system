<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
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
        // Store-wide settings (currency, tax) are available to every view as $store and $cur.
        View::composer('*', function ($view) {
            static $store = null;
            $store ??= \App\Models\StoreSetting::current();
            $view->with(['store' => $store, 'cur' => $store->currency]);
        });
    }
}
