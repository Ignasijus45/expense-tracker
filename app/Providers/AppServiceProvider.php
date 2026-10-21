<?php

namespace App\Providers;

use App\Repositories\CachedExpenseRepository;
use App\Repositories\ExpenseRepository;
use App\Repositories\ExpenseRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ExpenseRepositoryInterface::class, function ($app) {
            return new CachedExpenseRepository(
                $app->make(ExpenseRepository::class),
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
