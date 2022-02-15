<?php

namespace App\Providers;

use App\Services\LoanApplicationService;
use Illuminate\Support\ServiceProvider;

class LoanApplicationProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(LoanApplicationService::class, function ($app) {
            return new LoanApplicationService();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
