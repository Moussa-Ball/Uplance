<?php

namespace App;

use ScoutElastic\Searchable;
use \Conner\Tagging\Taggable;
use App\Http\Traits\Hashidable;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Model
{
    use Searchable, SoftDeletes, Hashidable, Taggable, Notifiable;

    /**
     * @var string
     */
    protected $indexConfigurator = JobIndexConfigurator::class;

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
            'project_name' => [
                'type' => 'text',
            ],
            'project_type' => [
                'type' => 'text',
            ],
            'location' => [
                'type' => 'text',
            ],
            'minimum' => [
                'type' => 'double',
            ],
            'maximum' => [
                'type' => 'double',
            ],
            'country' => [
                'type' => 'text',
            ],
            'skills' => [
                'type' => 'text',
            ],
            'description' => [
                'type' => 'text',
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
        'project_name',
        'project_type',
        'minimum',
        'maximum',
        'location',
        'skills',
        'country',
        'user_id',
        'category_id',
        'description',
    ];

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
    protected $hidden = ['id', 'user_id'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'skills' => 'array',
    ];

    public function getHashidAttribute()
    {
        return Hashids::connection(Job::class)->encode($this->attributes['id']);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bookmarks()
    {
        return $this->morphMany(Bookmark::class, 'bookmark');
    }

    public function proposals()
    {
        return $this->hasMany(Proposal::class);
    }
}
