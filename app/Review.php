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

    public function to()
    {
        return $this->belongsTo(User::class, 'to_id');
    }

    public function from()
    {
        return $this->belongsTo(User::class, 'from_id');
    }
}
