<?php

namespace Wilbere\UploadFiles\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class File extends Model
{

    protected $fillable = [
        'url'
    ];

    /**
     * Get image for models
     */
    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }

}