<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Builders\PostBuilder;
use App\Builders\BuilderInterface;

class BuilderServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(BuilderInterface::class, PostBuilder::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}