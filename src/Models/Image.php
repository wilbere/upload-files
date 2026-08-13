<?php

namespace Wilbere\UploadFiles\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{

    protected $fillable = [
        'url'
    ];

    /**
     * Boot the model.
     */
    protected static function booted()
    {
        static::deleted(function ($image) {
            if (Storage::disk('public')->exists($image->url)) {
                Storage::disk('public')->delete($image->url);
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