<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table = 'posts';

    protected $fillable = ['name', 'slug', 'see', 'image', 'content'];

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function scopeSearch($query, $keywords)
    {
        $searchValues = preg_split('/\s+/', $keywords, -1, PREG_SPLIT_NO_EMPTY);
        return $query->where(function ($q) use ($searchValues) {
            foreach ($searchValues as $value) {
                $q->orWhere('name', 'like', "%{$value}%");
                $q->orWhere('slug', 'like', "%{$value}%");
                $q->orWhere('content', 'like', "%{$value}%");
            }
        });
    }
}
