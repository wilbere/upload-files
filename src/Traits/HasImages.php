<?php

namespace Wilbere\UploadFiles\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Wilbere\UploadFiles\Models\Image;

class HasImages
{
    /**
     * A model may have multiple Images.
     */
    public function images(): MorphMany
    {
        $this->morphMany(Image::class, 'imageable');
    }


    /**
     * A model may have one image
     */
    public function image(): MorphOne
    {
        $this->morphOne(Image::class, 'fileable');
    }

}