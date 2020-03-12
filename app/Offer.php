<?php

namespace App;

use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Offer extends Model
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
        return Hashids::connection(Offer::class)->encode($this->attributes['id']);
    }

    public function to()
    {
        return $this->belongsTo(User::class, 'to_id');
    }

    public function from()
    {
        return $this->belongsTo(User::class, 'from_id');
    }

    public function proposal()
    {
        return $this->belongsTo(Proposal::class, 'proposal_id');
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
}
