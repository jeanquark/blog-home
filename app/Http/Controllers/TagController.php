<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Tag;

use Validator;
use Redirect;
use Session;
use Input;
use View;
use Form;


class TagController extends Controller
{   

    protected $rules = [        
        'name' => ['required', 'min:3'],        
        'slug' => ['required', 'min:2', 'alpha'],   
    ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = Tag::all();

        return View::make('admin.tag.index')->with('tags', $tags);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return View::make('admin.tag.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->rules);

        // Retrieve title and slug
        $input = Input::all();

        Tag::create($input);
     
        return Redirect::route('admin.tag.index')->with('success', 'New Tag created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //return View::make('admin.tag.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tag = Tag::find($id);

        return View::make('admin.tag.edit')->with('tag', $tag);
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
            'name' => ['required', 'min:3'],
            'slug'  => ['required', 'min:2', 'alpha']
        );

        $validator = Validator::make(Input::all(), $rules);


        // process the login
        if ($validator->fails()) {
            return Redirect::to('admin/tag/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput();
        } else {
            $tag = Tag::find($id);

            // Update title and slug
            $tag->name = Input::get('name');
            $tag->slug = Input::get('slug');
            $tag->color = Input::get('color');
                
            $tag->save();

            Session::flash('success', 'Successfully updated tag');
            return Redirect::to('admin/tag');

        } //end else
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $tag = Tag::find($id);

        // Delete reference in pivot table
        $tag->posts()->detach();

        $tag->delete();

        Session::flash('success', 'Successfully deleted tag!');
        return redirect::to('admin/tag');
    }
}