<?php

namespace Wilbere\UploadFiles\Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Wilbere\UploadFiles\Tests\Support\TestModel;
use Wilbere\UploadFiles\Tests\TestCase;

class UploadImagesTest extends TestCase
{
    public function test_it_can_upload_an_image()
    {
        Storage::fake('public');

        $model = TestModel::create(['name' => 'Test']);
        $image = UploadedFile::fake()->image('avatar.jpg');

        $model->uploadImage($image, 'avatars', 'public');

        $this->assertCount(1, $model->images);

        Storage::disk('public')->assertExists($model->images->first()->url);
    }

    public function test_it_deletes_physical_image_when_image_model_is_deleted()
    {
        Storage::fake('public');

        $model = TestModel::create(['name' => 'Test']);
        $image = UploadedFile::fake()->image('avatar.jpg');

        $uploadedImage = $model->uploadImage($image, 'avatars', 'public');
        
        Storage::disk('public')->assertExists($uploadedImage->url);

        // Delete the image model
        $uploadedImage->delete();

        Storage::disk('public')->assertMissing($uploadedImage->url);
        $this->assertCount(0, $model->refresh()->images);
    }

    public function test_it_deletes_physical_images_when_parent_model_is_deleted()
    {
        Storage::fake('public');

        $model = TestModel::create(['name' => 'Test']);
        $image1 = UploadedFile::fake()->image('avatar1.jpg');
        $image2 = UploadedFile::fake()->image('avatar2.jpg');

        $uploadedImage1 = $model->uploadImage($image1, 'avatars', 'public');
        $uploadedImage2 = $model->uploadImage($image2, 'avatars', 'public');

        // Delete the parent model
        $model->delete();

        Storage::disk('public')->assertMissing($uploadedImage1->url);
        Storage::disk('public')->assertMissing($uploadedImage2->url);
        
        // Assert images are deleted from DB
        $this->assertDatabaseMissing('images', ['id' => $uploadedImage1->id]);
        $this->assertDatabaseMissing('images', ['id' => $uploadedImage2->id]);
    }
}
