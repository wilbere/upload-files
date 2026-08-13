<?php

namespace Wilbere\UploadFiles\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

class File extends Model
{

    protected $fillable = [
        'name', 'path'
    ];

    /**
     * Boot the model.
     */
    protected static function booted()
    {
        static::deleted(function ($file) {
            if (Storage::disk('public')->exists($file->path)) {
                Storage::disk('public')->delete($file->path);
            }
        });
    }

    /**
     *  Return relationships.
     */
    public function fileable(): MorphTo
    {
        return $this->morphTo();
    }

}