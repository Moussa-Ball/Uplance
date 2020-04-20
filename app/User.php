<?php

namespace App;

use App\Connect\StripeConnect;
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
    use Messagable,
        Billable,
        SoftDeletes,
        Hashidable,
        Taggable,
        HasApiTokens,
        Notifiable,
        Searchable,
        StripeConnect;

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
        'connect_id',
        'connect_verified',
        'escrow',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id',
        'role_id',
        'password',
        'remember_token',
        'email',
        'provider',
        'provider_id',
        'next_withdraw_date',
        'next_reset_date',
        'address',
        'postal_code',
        'mobile_phone',
        'email_verified_at',
        'settings',
        'created_at',
        'updated_at',
        'deleted_at',
        'stripe_id',
        'card_brand',
        'card_last_four',
        'trial_ends_at',
    ];

    /**
     * The attributes that should be append.
     *
     * @var array
     */
    protected $appends = ['hashid', 'country_name', 'premium', 'rating_format'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getRatingFormatAttribute()
    {
        return number_format($this->rating, 1, '.', '');
    }

    public function getHashidAttribute()
    {
        return Hashids::connection(User::class)->encode($this->attributes['id']);
    }

    public function getCountryNameAttribute()
    {
        if ($this->country) {
            return \PragmaRX\Countries\Package\Countries::where('cca2', strtoupper($this->country))
                ->first()->name->common;
        } else {
            return "";
        }
    }

    public function getPremiumAttribute()
    {
        if ($this->subscribed('pro')) {
            return 'pro';
        } else if ($this->subscribed('business')) {
            return 'business';
        }
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function jobs()
    {
        return $this->hasMany(User::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'to_id');
    }

    public function bookmarks()
    {
        return $this->morphMany(Bookmark::class, 'bookmark');
    }

    public function incrementProfileView()
    {
        $profileViewModel = new ProfileView;
        $profileViewModel->user_id = $this->id;
        $profileViewModel->save();
    }

    public function profileViews()
    {
        return $this->hasMany(ProfileView::class, 'user_id');
    }
}
