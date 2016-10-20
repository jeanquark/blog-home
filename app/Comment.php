<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

    protected $dates = ['published_at'];

    protected $fillable = [
        'post_id', 'name', 'message', 'published_at'
    ];

    /**
     * The posts that belong to the tag.
     */
    public function post()
    {
        return $this->belongsTo('App\Post');
    }

    public function commentReply()
    {
        return $this->hasMany('App\CommentReply', 'comment_replies');
    }
}