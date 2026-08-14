<?php

namespace Wilbere\UploadFiles\Tests\Support;

use Illuminate\Database\Eloquent\Model;
use Wilbere\UploadFiles\Traits\HasFiles;
use Wilbere\UploadFiles\Traits\HasImages;

class TestModel extends Model
{
    use HasFiles, HasImages;

    protected $table = 'test_models';
    protected $guarded = [];
}
