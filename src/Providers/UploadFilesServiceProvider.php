<?php

namespace Wilbere\UploadFiles\Providers;

use Illuminate\Support\ServiceProvider;

class UploadFilesServiceProvider extends ServiceProvider
{
    public function register()
    {
        
    }


    public function boot(): void
    {
        $this->publishesMigrations([
            __DIR__. '/../database/migrations/' => database_path('migrations'),
        ]);
    }
}