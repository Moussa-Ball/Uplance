<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    public $guarded = [];

    public function bookmark()
    {
        return $this->morphTo();
    }
}
