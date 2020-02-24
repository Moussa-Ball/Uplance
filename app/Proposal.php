<?php

namespace App;

use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proposal extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    /**
     * The attributes that should be append.
     *
     * @var array
     */
    protected $appends = ['hashid'];

    /**
     * The attributes that should be hidden.
     *
     * @var array
     */
    protected $hidden = ['id'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'milestones' => 'array',
    ];

    public function getHashidAttribute()
    {
        return Hashids::connection(Job::class)->encode($this->attributes['id']);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
}
