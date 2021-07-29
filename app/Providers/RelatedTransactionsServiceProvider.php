<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Support\RelatedTransactions;

class RelatedTransactionsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('related-transactions', function () {
            return new RelatedTransactions();
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
