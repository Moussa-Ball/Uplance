<?php

namespace App;

use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoices';

    protected $fillable = [
        'order',
        'issued',
        'description',
        'type',
        'rate',
        'service_fee',
        'amount',
        'payment_id',
        'payer_id',
        'hours',
        'total',
        'total_due',
        'paid_at',
        'due_date',
        'to_id',
        'job_id',
        'from_id',
        'contract_id',
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
        return Hashids::connection(Invoice::class)->encode($this->attributes['id']);
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }

    public function to()
    {
        return $this->belongsTO(User::class, 'to_id');
    }

    public function from()
    {
        return $this->belongsTO(User::class, 'from_id');
    }
}
