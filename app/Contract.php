<?php

namespace App;

use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $table = 'contracts';

    protected $fillable = [
        'title',
        'type',
        'rate',
        'work_hours',
        'remaining',
        'project_paid',
        'amount',
        'budget',
        'milestones_paid',
        'total_earnings',
        'due_date',
        'milestones',
        'is_agency',
        'completed',
        'job_id',
        'to_id',
        'from_id',
        'team_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'milestones' => 'array',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id'
    ];

    /**
     * The attributes that should be append.
     *
     * @var array
     */
    protected $appends = ['hashid'];

    public function getHashidAttribute()
    {
        return Hashids::connection(Contract::class)->encode($this->attributes['id']);
    }

    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
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
        return $this->belongsTo(Proposal::class, 'to_id', 'user_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'to_id', 'from_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
