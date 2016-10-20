<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use App\Role;

use Activation;
use Validator;
use Redirect;
use Sentinel;
use Session;
use Input;
use View;
use Hash;


class UserController extends Controller
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function oneUser($id)
    {
        return $this->user->oneUser($id);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();

        //return $users;
        return View::make('admin.user.index')->with('users', $users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        //$roles = Role::all();
        $roles = Role::orderBy('id', 'desc')->pluck('name', 'id');

        return View::make('admin.user.create')->with('roles', $roles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = array(
            'email' => 'required|min:4|max:254|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required',
            'first_name' => 'min:2|max:32|alpha_dash',
            'last_name' => 'min:2|max:32|alpha_dash',
        );

        $validator = Validator::make(Input::all(), $rules);


        // process the login
        if ($validator->fails()) {
            return Redirect::to('admin/user/create')
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        } else {
            $credentials = [
                'email'    => Input::get('email'),
                'password' => Input::get('password'),
                'first_name' => Input::get('first_name'),
                'last_name' => Input::get('last_name')
            ];

            $user = Sentinel::registerAndActivate($credentials);
        }

        $role = Input::get('roles');

        if ($user) {
            // Attach role to user
            $user = User::find($user->id); 
            $user->roles()->attach($role);
        }

        return Redirect::Route('admin.user.index')->with('success', 'New User created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);

        return View::make('admin.user.show')->with('user', $user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::orderBy('id', 'desc')->get();

        return View::make('admin.user.edit')
            ->with('user', $user)
            ->with('roles', $roles);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = array(
            'first_name' => 'min:2|max:32|alpha_dash',
            'last_name' => 'min:2|max:32|alpha_dash',
        );

        $validator = Validator::make(Input::all(), $rules);


        // process the login
        if ($validator->fails()) {
            return Redirect::to('admin/user/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        } else {
            $user = User::find($id);
            $user->first_name = Input::get('first_name');
            $user->last_name = Input::get('last_name');

            $user->save();

            Session::flash('success', 'Successfully updated user!');
            return Redirect::to('admin/user');

        } //end else
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);

        // Delete reference in pivot table
        $user->roles()->detach();

        $user->delete();

        Session::flash('success', 'successfully deleted user!');
        return redirect::to('admin/user');
    }


    public function updateActivation($id)
    {
        $user = Sentinel::findById($id);

        $activation = Activation::completed($user);
        
        if (!$activation) { // If user is not active

            // Remove all uncompleted activation records
            $deleteRows = Activation::where('user_id', '=', $user->id)->delete();

            $activate = Activation::create($user); // Create new activation record
            $code = $activate->code; // Retrieve activation code
            Activation::complete($user, $code); // Complete activation

            Session::flash('success', 'Successfully activate account!');
            return Redirect::to('admin/user');

        } else { // If user is active

            //Remove all activation records
            $deleteRows = Activation::where('user_id', '=', $user->id)->delete();

            Session::flash('success', 'Successfully removed activation!');
            return Redirect::to('admin/user');
        }

    }


    public function updateRole($id)
    {
        $user = Sentinel::findById($id);
        $role_id = Input::get('role');
        $role = Role::where('id', '=', $role_id)->first();

        if (!($user->roles()->get()->isEmpty())) { // Check if user already has a role
            $detach = $user->roles()->detach();     // Detach current role
            if ($detach) {
                $attach = $user->roles()->attach($role); // Attach new role
                if ($role->users->contains($user->id)) { // Check if attachement worked
                    Session::flash('success', 'Successfully updated user role!');
                    return Redirect::to('admin/user');
                } else { // Could not attach new role
                    Session::flash('error', 'Could not define new role.');
                    return Redirect::back();
                }
            } else { // Could not detach old role
                Session::flash('error', 'Could not erase old role.');
                return Redirect::back();
            }
        } else { // User has no predefined role
            $attach = $user->roles()->attach($role);
            if ($role->users->contains($user->id)) { // Check if attachement worked
                Session::flash('success', 'Successfully updated user role!');
                return Redirect::to('admin/user');
            } else { // Could not attach new role
                Session::flash('error', 'Could not define new role.');
                return Redirect::back();
            }
        }
    }


    public function changePassword($id)
    {
        $rules = array(
            'new_password'  => ['required', 'min:6', 'confirmed'],
            'new_password_confirmation' => ['required']
        );

        $validator = Validator::make(Input::all(), $rules);

        // check if new password format is valid
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator);
        } else { // valid format
            $user = Sentinel::findById($id);

            $credentials = [
                'email'    => $user->email,
                'password' => Input::get('old_password'),
            ];
            
            // First check if account has been activated
            if (Activation::completed($user)) {
                // If it is the case check credentials
                if (Sentinel::authenticate($credentials)) { // True if valid credentials
                    $user->password = Hash::make(Input::get('new_password'));
                    $user->save();

                    if ($user->save()) {
                        Session::flash('success', 'Your password has been updated!');
                        return Redirect::back();
                    } else {
                        Session::flash('error', 'Sorry, there was an error.');
                        return Redirect::back();
                    }
                } else { // No valid credentials
                    Session::flash('error', 'Incorrect password.');
                    return Redirect::back();
                }
            } else { // Account has not been activated
                Session::flash('error', 'Your account has not been activated yet.');
                return Redirect::back();
            }
        }
    }
}