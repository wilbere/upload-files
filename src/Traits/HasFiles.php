<?php

namespace Wilbere\UploadFiles\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Wilbere\UploadFiles\Models\File;

class HasFiles
{
    /**
     * A model may have multiple files.
     */
    public function files(): MorphMany
    {
        $this->morphMany(File::class, 'fileable');
    }


    /**
     * A model may have one file
     */
    public function file(): MorphOne
    {
        $this->morphOne(File::class, 'fileable');
    }

}