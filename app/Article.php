<?php

namespace App;

use ScoutElastic\Searchable;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use Searchable;

    protected $fillable = ['name', 'slug', 'see', 'image', 'content'];

    /**
     * @var string
     */
    protected $indexConfigurator = ArticleIndexConfigurator::class;

    /**
     * @var array
     */
    protected $searchRules = [];

    /**
     * @var array
     */
    protected $mapping = [
        'properties' => [
            'name' => [
                'type' => 'text',
            ],
            'slug' => [
                'type' => 'text',
            ],
            'see' => [
                'type' => 'integer',
            ],
            'content' => [
                'type' => 'text',
            ],
            'created_at' => [
                'type' => 'date'
            ],
        ]
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
