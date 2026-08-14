<?php

namespace Wilbere\UploadFiles\Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Wilbere\UploadFiles\Tests\Support\TestModel;
use Wilbere\UploadFiles\Tests\TestCase;

class UploadFilesTest extends TestCase
{
    public function test_it_can_upload_a_file()
    {
        Storage::fake('public');

        $model = TestModel::create(['name' => 'Test']);
        $file = UploadedFile::fake()->create('document.pdf', 100);

        $model->uploadFile($file, 'documents', 'public');

        $this->assertCount(1, $model->files);
        $this->assertEquals('document.pdf', $model->files->first()->name);

        Storage::disk('public')->assertExists($model->files->first()->path);
    }

    public function test_it_deletes_physical_file_when_file_model_is_deleted()
    {
        Storage::fake('public');

        $model = TestModel::create(['name' => 'Test']);
        $file = UploadedFile::fake()->create('document.pdf', 100);

        $uploadedFile = $model->uploadFile($file, 'documents', 'public');
        
        Storage::disk('public')->assertExists($uploadedFile->path);

        // Delete the file model
        $uploadedFile->delete();

        Storage::disk('public')->assertMissing($uploadedFile->path);
        $this->assertCount(0, $model->refresh()->files);
    }

    public function test_it_deletes_physical_files_when_parent_model_is_deleted()
    {
        Storage::fake('public');

        $model = TestModel::create(['name' => 'Test']);
        $file1 = UploadedFile::fake()->create('document1.pdf', 100);
        $file2 = UploadedFile::fake()->create('document2.pdf', 100);

        $uploadedFile1 = $model->uploadFile($file1, 'documents', 'public');
        $uploadedFile2 = $model->uploadFile($file2, 'documents', 'public');

        // Delete the parent model
        $model->delete();

        Storage::disk('public')->assertMissing($uploadedFile1->path);
        Storage::disk('public')->assertMissing($uploadedFile2->path);
        
        // Assert files are deleted from DB
        $this->assertDatabaseMissing('files', ['id' => $uploadedFile1->id]);
        $this->assertDatabaseMissing('files', ['id' => $uploadedFile2->id]);
    }
}
