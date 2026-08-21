<?php

namespace Wilbere\UploadFiles\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Wilbere\UploadFiles\Models\Image;

trait HasImages
{
    /**
     * Boot the trait to listen for the deleting event.
     */
    public static function bootHasImages()
    {
        static::deleting(function ($model) {
            $model->images->each(function ($image) {
                $image->delete();
            });
        });
    }

    /**
     * A model may have multiple Images.
     */
    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    /**
     * A model may have one image
     */
    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    /**
     * Upload and attach an image to the model.
     */
    public function uploadImage(UploadedFile $uploadedFile, string $folder = 'images', string $disk = 'public'): Image
    {
        $manager = app(ImageManager::class);
        $image = $manager->read($uploadedFile);
        
        // Comprimir agresivamente a formato WebP (calidad 80%)
        $encoded = $image->toWebp(80);
        
        $filename = Str::random(40) . '.webp';
        $path = trim($folder, '/') . '/' . $filename;
        
        // Subir al disco correspondiente (soporta s3/r2 de forma nativa)
        Storage::disk($disk)->put($path, (string) $encoded);

        return $this->images()->create([
            'url' => $path,
            'disk' => $disk,
        ]);
    }
}