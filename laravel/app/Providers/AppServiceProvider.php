<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Todo\Infrastructure\Persistence\Eloquent\Repository\TaskRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'Todo\Domain\Repository\TaskRepositoryInterface',
            TaskRepository::class
        );
    }
}
