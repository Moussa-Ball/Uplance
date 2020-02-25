<?php

namespace App;

use ScoutElastic\Searchable;
use Laravel\Cashier\Billable;
use \Conner\Tagging\Taggable;
use App\Http\Traits\Hashidable;
use Laravel\Passport\HasApiTokens;
use Vinkla\Hashids\Facades\Hashids;
use Cmgmyr\Messenger\Traits\Messagable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends \TCG\Voyager\Models\User implements MustVerifyEmail
{
    use Messagable, Billable, SoftDeletes, Hashidable, Taggable, HasApiTokens, Notifiable, Searchable;

    /**
     * @var string
     */
    protected $indexConfigurator = UserIndexConfigurator::class;

    /**
     * @var array
     */
    protected $searchRules = [
        //
    ];

    /**
     * @var array
     */
    protected $mapping = [
        'properties' => [
            'first_name' => [
                'type' => 'text',
            ],
            'last_name' => [
                'type' => 'text',
            ],
            'name' => [
                'type' => 'text',
            ],
            'total_earning' => [
                'type' => 'double',
            ],
            'rating' => [
                'type' => 'double',
            ],
            'jobs_done' => [
                'type' => 'integer',
            ],
            'rehired' => [
                'type' => 'integer',
            ],
            'global_rank' => [
                'type' => 'integer',
            ],
            'job_success' => [
                'type' => 'integer',
            ],
            'recommendation' => [
                'type' => 'integer',
            ],
            'on_time' => [
                'type' => 'integer',
            ],
            'on_budget' => [
                'type' => 'integer',
            ],
            'current_account' => [
                'type' => 'keyword',
            ],
            'hourly_rate' => [
                'type' => 'integer',
            ],
            'tagline' => [
                'type' => 'text',
            ],
            'city' => [
                'type' => 'text',
                'analyzer' => 'english'
            ],
            'address' => [
                'type' => 'text',
            ],
            'country' => [
                'type' => 'keyword',
            ],
            'skills' => [
                'type' => 'text',
            ],
            'presentation' => [
                'type' => 'text',
            ],
            'category_id' => [
                'type' => 'integer',
            ],
            'created_at' => [
                'type' => 'date',
                "format" => "yyyy-MM-dd HH:mm:ss||yyyy-MM-dd||epoch_millis",
            ],
        ]
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'name',
        'email',
        'password',
        'avatar',
        'provider',
        'provider_id',
        'account_type',
        'current_account',
        'category_id',
        'presence_status',
        'switcher_status',
        'credit',
        'hourly_rate',
        'skills',
        'tagline',
        'city',
        'address',
        'country',
        'mobile_phone',
        'presentation',
        'postal_code',
    ];

    /**
     * The attributes that should be append.
     *
     * @var array
     */
    protected $appends = ['hashid'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id', 'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getHashidAttribute()
    {
        return Hashids::connection(User::class)->encode($this->attributes['id']);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function jobs()
    {
        return $this->hasMany(User::class);
    }

    public function bookmarks()
    {
        return $this->morphMany(Bookmark::class, 'bookmark');
    }
}
