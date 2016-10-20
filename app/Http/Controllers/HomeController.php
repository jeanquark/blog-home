<?php namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use App\Http\Controllers\Controller;
use App\User;
use App\Post;

use Hashids\Hashids;
use Validator;
use Redirect;
use Response;
use Input;
use View;
use Mail;


class HomeController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */


    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function index() {

        $posts = Post::where('is_published', '=', true)->orderBy('published_at', 'desc')->simplePaginate(2);

        //dd($posts[0]->id);

        $hashids = new Hashids();

        return View::make('home')
            ->with('posts', $posts)
            ->with('hashids', $hashids);

    }


    public function about() {

        return View::make('about');

    }


    public function services() {

        return View::make('services');

    }


    public function contact() {

        return View::make('contact');

    }

    public function process_contact() {

        // Get all the data and store it inside Store Variable
        $data = Input::all();

        // Validation rules
        $rules = array (
            'name' => 'required|alpha|max:128',
            'email' => 'required|email',
            'message' => 'required|max:2048'
        );

        $data1 = [
            'email' => Input::get('email'),
            'name'  => Input::get('name'),
            'message'  => Input::get('message'),
            'token' => Input::get('_token'),
        ];

        // Validate data
        $validator  = Validator::make ($data, $rules);


        if ($validator -> passes()) {

            $data = Input::all();

            $data['messageLines'] = explode("\n", Input::get('message'));
            
            Mail::send('emails.contact', $data, function ($message) use ($data) {
                $message->from('hello@app.com', 'Message from your blog');

                $message->to(getenv('MAIL_USERNAME'))->subject('Message from your blog');
            });

            return Redirect::to('/contact')->with('success', 'Thank you for contacting us! Your message has been sent.');

        } else {
            // Return contact form with errors
            return Redirect::to('/contact')->withErrors($validator)->withInput();
        }
    }
}