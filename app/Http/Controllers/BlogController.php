<?php namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use App\Http\Controllers\Controller;
use App\CommentReply;
use App\Comment;
use App\User;
use App\Post;
use App\Tag;

use Hashids\Hashids;
use Validator;
use Redirect;
use Response;
use Session;
use Input;
use View;


class BlogController extends Controller
{
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function index() {
    }


    public function showPost($slug) {

        $post = Post::where('slug', '=', $slug)->first();
        $comments = Comment::where('is_published', '=', true)->where('post_id', '=', $post->id)->get();
        
        $replies = '';
        foreach ($comments as $comment) {
            $replies = CommentReply::where('is_published', '=', true)->where('comment_id', '=', $comment->id)->get();
        }

        $hashids = new Hashids();

        return View::make('post')
            ->with('post', $post)
            ->with('comments', $comments)
            ->with('replies', $replies)
            ->with('hashids', $hashids);

    }


    public function showTaggedPost($slug) {

        $tag = Tag::where('slug', '=', $slug)->first();

        $base_query = $tag->posts()->where('is_published', '=', true)->orderBy('published_at', 'desc');
        $count = $base_query->count();
        $posts = $base_query->simplePaginate(1);
        
        return View::make('tag')
            ->with('posts', $posts)
            ->with('count', $count)
            ->with('tag', $tag);

    }


    public function showUserPost($hash) {

        $hashids = new Hashids();
        $id = $hashids->decode($hash);

        $user = User::where('id', '=', $id)->first();
        $abc = $user;

        $base_query = $user->posts()->where('is_published', '=', true)->orderBy('published_at', 'desc');
        $count = $base_query->count();
        $posts = $base_query->simplePaginate(1);

        return View::make('user')
            ->with('posts', $posts)
            ->with('user', $user)
            ->with('count', $count);

    }


    public function showSearchedPost($slug) {

        $query = Input::get('search');
        $base_query = Post::where('title', 'LIKE', '%' . $query . '%')->orWhere('content', 'LIKE', '%' . $query . '%');
        $count = $base_query->count();
        $posts = $base_query->paginate(2);

        return View::make('search')->with('posts', $posts)->with('count', $count)->with('query', $query);
    }


    public function search() {
        $query = Input::get('search');
        $base_query = Post::where('title', 'LIKE', '%' . $query . '%')->orWhere('content_html', 'LIKE', '%' . $query . '%');
        $count = $base_query->count();
        $posts = $base_query->paginate(2);
     
        return View::make('search', compact('posts', 'count', 'query'));
    }



    public function comment($slug) {

        $post = Post::where('slug', '=', $slug)->first();

        $rules = array(
            'comment_name' => ['required', 'min:2', 'max:32', "regex:/^[a-zA-Z0-9?$@#()'!,+\-=_:.&€£*%\s]+$/"],
            'message'  => ['required', 'min:2', 'max:200', 'string']
        );

        $validator = Validator::make(Input::all(), $rules);


        // process the login
        if ($validator->fails()) {
            return Redirect::to('post/' . $slug)
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        } else {
            $post = Post::where('slug', '=', $slug)->first();

            $comment = new Comment;

            // Insert name and comment
            $comment->post_id = $post->id;
            $comment->name = Input::get('comment_name');
            $comment->message = Input::get('message');

            $comment->save();

            Session::flash('success', 'Thank you. Your comment has been sent and will appear once accepted.');
            return Redirect::back();

        }
    }


    public function reply($id) {

        $rules = array(
            'reply_name' => ['required', 'min:3', 'max:16', 'alpha'],
            'reply'  => ['required', 'min:2', 'max:200', 'string']
        );

        $validator = Validator::make(Input::all(), $rules);


        // process the login
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        } else {

            $comment = Comment::where('id', '=', $id)->first();

            $reply = new CommentReply;

            // Insert name and reply
            $reply->comment_id = $comment->id;
            $reply->name = Input::get('reply_name');
            $reply->message = Input::get('reply');

            $reply->save();

            Session::flash('success', 'Thank you. Your reply has been sent and will appear once accepted.');
            return Redirect::back();
        }
    }
}