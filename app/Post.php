<?php namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Services\Markdowner;
use Carbon\Carbon;

class Post extends Model
{
    protected $dates = ['published_at'];

    protected $fillable = [
        'title', 'slug', 'content', 'image_path', 'published_at', 
    ];

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;

    if (! $this->exists) {
        $this->attributes['slug'] = str_slug($value);
        }
    }

    /**
     * The tags that belong to the post.
     */
    public function tags()
    {
        return $this->belongsToMany('App\Tag', 'post_tags');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}