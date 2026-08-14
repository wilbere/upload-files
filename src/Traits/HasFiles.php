<?php

namespace Wilbere\UploadFiles\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Http\UploadedFile;
use Wilbere\UploadFiles\Models\File;

trait HasFiles
{
    /**
     * Boot the trait to listen for the deleting event.
     */
    public static function bootHasFiles()
    {
        static::deleting(function ($model) {
            $model->files->each(function ($file) {
                $file->delete();
            });
        });
    }

    /**
     * A model may have multiple files.
     */
    public function files(): MorphMany
    {
        return $this->morphMany(File::class, 'fileable');
    }


    /**
     * A model may have one file
     */
    public function file(): MorphOne
    {
        return $this->morphOne(File::class, 'fileable');
    }

    /**
     * Upload and attach a file to the model.
     */
    public function uploadFile(UploadedFile $uploadedFile, string $folder = 'files', string $disk = 'public'): File
    {
        $path = $uploadedFile->store($folder, $disk);

        return $this->files()->create([
            'name' => $uploadedFile->getClientOriginalName(),
            'path' => $path,
        ]);
    }

}