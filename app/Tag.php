<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
	protected $fillable = [
        'name', 'slug'
    ];

    /**
     * The posts that belong to the tag.
     */
    public function posts()
    {
        return $this->belongsToMany('App\Post', 'post_tags');
    }
}