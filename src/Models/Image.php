<?php

namespace Wilbere\UploadFiles\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{

    protected $fillable = [
        'url',
        'disk'
    ];

    /**
     * Boot the model.
     */
    protected static function booted()
    {
        static::deleted(function ($image) {
            $disk = $image->disk ?? 'public';
            if (Storage::disk($disk)->exists($image->url)) {
                Storage::disk($disk)->delete($image->url);
            }
        });
    }

    /**
     * Get image for models
     */
    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }

}