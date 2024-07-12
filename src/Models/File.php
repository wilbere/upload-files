<?php

namespace Wilbere\UploadFiles\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class File extends Model
{

    protected $fillable = [
        'name', 'path'
    ];

    /**
     *  Return relationships.
     */
    public function filable(): MorphTo
    {
        return $this->morphTo();
    }

}