<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

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
        // Make school branding available to every view as $school.
        View::composer('*', function ($view) {
            $view->with('school', [
                'name'     => setting('school_name', config('app.name', 'School Portal')),
                'tagline'  => setting('school_tagline', ''),
                'logo'     => setting('school_logo'),
                'color'    => setting('primary_color', '#2563eb'),
                'currency' => setting('currency_symbol', '₦'),
                'term'     => setting('current_term', ''),
                'session'  => setting('current_session', ''),
            ]);
        });
    }
}
