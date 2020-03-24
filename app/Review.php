<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $guarded = [];

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }
}
