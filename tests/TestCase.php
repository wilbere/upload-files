<?php

namespace Wilbere\UploadFiles\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Wilbere\UploadFiles\Providers\UploadFilesServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();
    }

    protected function getPackageProviders($app)
    {
        return [
            UploadFilesServiceProvider::class,
        ];
    }

    protected function setUpDatabase()
    {
        // Run package migrations
        $filesMigration = include __DIR__.'/../database/migrations/create_files_table.php.stub';
        $filesMigration->up();

        $imagesMigration = include __DIR__.'/../database/migrations/create_images_table.php.stub';
        $imagesMigration->up();

        // Create dummy table for TestModel
        Schema::create('test_models', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->timestamps();
        });
    }
}
