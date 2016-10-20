<?php namespace App\Http\Controllers;

use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

use App\User;
use App\Role;
use View;
use Redirect;
use Session;
use Input;
use Sentinel;
use Activation;

class SessionController extends Controller
{
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function create() {

        // Retrieve admin email and password
        $query = Role::where('slug', '=', 'admin')->first();
        
        if ($query) {
            $user = $query->users()->first();
        } else {
            $user = NULL;
        }

        //dd($user);
        return View::make('login')->with('user', $user);
        //return View::make('login');
    }


    public function store() {
        
        $credentials = [
            'email' => Input::get('email'),
            'password' => Input::get('password'),
        ];

        try {
            $user = Sentinel::findByCredentials($credentials);

            if (Input::get('remember') == 'on') {
                if (Sentinel::authenticateAndRemember($credentials)) {
                    Sentinel::login($user);

                    //return Redirect::route('back.index')->with('success', 'Welcome <b>' . $user->email);
                    $greetings = isset($user->username) ? $user->username : $user->email;
                    return Redirect::route('admin.index')->with('success', 'Welcome <b>' . $greetings . ' !');
                } else {
                    return Redirect::route('login')->with('error', 'Incorrect credentials')->withInput(Input::except('password'));
                }
            } elseif (Sentinel::authenticate($credentials)) {
                Sentinel::login($user);

                //return Redirect::route('back.index')->with('success', 'Welcome <b>' . $user->email);
                $greetings = isset($user->username) ? $user->username : $user->email;
                return Redirect::route('admin.index')->with('success', 'Welcome <b>' . $greetings . ' !');
            } else {
                return Redirect::route('login')->with('error', 'Incorrect credentials')->withInput(Input::except('password'));
            }
        } catch (NotActivatedException $e) {
            return Redirect::route('login')->with('error', $e->getMessage());
        }

    }


    public function logout() {
        
        Sentinel::logout();

        return Redirect::route('home');

    }

}
