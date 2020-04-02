<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'taggers';

    public $guarded = [];
    public $timestamps = false;

    public function articles()
    {
        return $this->belongsToMany(Article::class);
    }
}
