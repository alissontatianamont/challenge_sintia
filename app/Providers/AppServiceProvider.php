<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\FileInterface;
use App\Services\FileService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(FileInterface::class, FileService::class);
        $this->app->bind(\App\Contracts\GoogleDriveRepositoryInterface::class, \App\Repositories\GoogleDriveRepository::class);
        $this->app->bind(\App\Contracts\GoogleDriveServiceInterface::class, \App\Services\GoogleDriveService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
