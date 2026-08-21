<?php

namespace Wilbere\UploadFiles\Providers;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;

class UploadFilesServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(ImageManager::class, function ($app) {
            $driverName = config('upload-files.image_driver', 'gd');
            
            $driver = match ($driverName) {
                'imagick' => new ImagickDriver(),
                default => new GdDriver(),
            };
            
            return new ImageManager($driver);
        });
    }


    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../../database/migrations/create_files_table.php.stub' => $this->getMigrationFileName('create_files_tables.php'),
            __DIR__.'/../../database/migrations/create_images_table.php.stub' => $this->getMigrationFileName('create_images_tables.php'),
        ], 'upload-file-migration');
    }

    /**
     * Returns existing migration file if found, else uses the current timestamp.
     */
    public function getMigrationFileName(string $migrationFileName): string
    {
        $timestamp = date('Y_m_d_His');

        $filesystem = $this->app->make(Filesystem::class);

        return Collection::make([$this->app->databasePath().DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR])
            ->flatMap(fn ($path) => $filesystem->glob($path.'*_'.$migrationFileName))
            ->push($this->app->databasePath()."/migrations/{$timestamp}_{$migrationFileName}")
            ->first();
    }
}