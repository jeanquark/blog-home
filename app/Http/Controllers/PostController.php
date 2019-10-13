<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Post;
use App\Tag;

use \Michelf\MarkdownExtra;
use Carbon\Carbon;
use Validator;
use Redirect;
use Sentinel;
use Session;
use Input;
use View;
use Form;


class PostController extends Controller
{
    protected $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function index()
    {
        $posts = $this->post->all();

        return View::make('admin.post.index')
            ->with('posts', $posts);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tags = Tag::pluck('name', 'id');
        return View::make('admin.post.create')->with('tags', $tags);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\StorePost $request)
    {
        $post = new Post;

        $post->user_id = Sentinel::getUser()->id;
        $post->title = Input::get('title');
        $post->slug = Input::get('slug');
        $post->content_raw = Input::get('text');
        $post->content_html = MarkdownExtra::defaultTransform(Input::get('text'));

        if (Input::hasfile('image')) {
            $extension = Input::file('image')->getClientOriginalExtension(); // Get image extension
            $now = new \DateTime(); // Get date and time
            $date = $now->getTimestamp(); // Convert date and time in timestamp
            $fileName = $date . '.' . $extension; // Give name to image
            $destinationPath = 'pictures'; // Define destination path
            $img = Input::file('image')->move($destinationPath, $fileName); // Upload image to destination path
            $new_path = $destinationPath . '/' . $fileName; // Write image path in DB
            $post->image_path = $new_path;

            // Resize image
            $filename = $new_path; // Get image

            // Resize image to format 900px/300px
            $size = getimagesize($filename); // Get image size

            $ratio = $size[0] / $size[1]; // Get ratio width/height

            if ($ratio > 3) { // If ratio is greater than optimal (900px/300px)
                $new_width = $size[0] / ($size[1] / 300);
                $new_height = 300;
                $shift_x = (($new_width - 900) * ($size[0] / $new_width)) / 2;
                $shift_y = 0;
            } elseif ($ratio < 3) { // If ratio is less than optimal (900px/300px)
                $new_width = 900;
                $new_height = $size[1] / ($size[0] / 900);
                $shift_x = 0;
                $shift_y = (($new_height - 300) * ($size[1] / $new_height)) / 2; //should be equal to 330 or 220
            } else { // If ratio is already optimal (900px/300px = 3)
                $new_width = 900;
                $new_height = 300;
                $shift_x = 0; // No need to crop horizontally
                $shift_y = 0; // No need to crop vertically
            }

            $src = imagecreatefromstring(file_get_contents($filename));

            $dst = imagecreatetruecolor(900, 300);
            imagecopyresampled($dst, $src, 0, 0, $shift_x, $shift_y, $new_width, $new_height, $size[0], $size[1]);
            imagedestroy($src); // Free up memory
            imagejpeg($dst, $filename, 100); // adjust format as needed
            imagedestroy($dst);

        } // end Input::hasfile('image')

        $tags = Input::get('tags');

        $post->save();

        if ($post->save()) {
            $post_id = $post->id;

            $post = Post::find($post_id);
            if (Input::has('tags')) {
                foreach ($tags as $tag) {
                    $post->tags()->attach($tag);
                }
            }
        }

        return response()->json(array($post));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);

        return View::make('admin.post.show')->with('post', $post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::find($id);
        $tags = Tag::all();
        $tags1 = Tag::lists('name', 'id');

        //Check if there is linked tags
        if (!empty($post->tags->first()->id)) {
            foreach ($post->tags as $tag) {
                $tags2[] = $tag->id;
            }
        } else {
            $tags2 = '';
        }

        return View::make('admin.post.edit')
            ->with('post', $post)
            ->with('tags', $tags)
            ->with('tags1', $tags1)
            ->with('tags2', $tags2);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\UpdatePost $request, $id)
    {
            $post = Post::find($id);

            // Update title and slug
            $post->title = Input::get('title');
            $post->slug = Input::get('slug');

            $post->content_raw = Input::get('text');
            $post->content_html = MarkdownExtra::defaultTransform(Input::get('text'));

            // If a new image is uploaded
            if (Input::hasfile('image')) {
                $extension = Input::file('image')->getClientOriginalExtension(); // Get image extension
                $now = new \DateTime(); // Get date and time
                $date = $now->getTimestamp(); // Convert date and time in timestamp
                $fileName = $date . '.' . $extension; // Give name to image
                $destinationPath = 'pictures'; // Define destination path
                $img = Input::file('image')->move($destinationPath, $fileName); // Upload image to destination path
                $new_path = $destinationPath . '/' . $fileName; // Write image path in DB
                $post->image_path = $new_path;

                // Resize image
                $filename = $new_path; // Get image

                // Resize image to format 900px/300px
                $size = getimagesize($filename); // Get image size
                $ratio = $size[0] / $size[1]; // Get ratio width/height
                if ($ratio > 3) { // If ratio is greater than optimal (900px/300px)
                    $new_width = $size[0] / ($size[1] / 300);
                    $new_height = 300;
                    $shift_x = (($new_width - 900) * ($size[0] / $new_width)) / 2;
                    $shift_y = 0;
                } elseif ($ratio < 3) { // If ratio is less than optimal (900px/300px)
                    $new_width = 900;
                    $new_height = $size[1] / ($size[0] / 900);
                    $shift_x = 0;
                    $shift_y = (($new_height - 300) * ($size[1] / $new_height)) / 2; //should be equal to 330 or 220
                } else { // If ratio is already optimal (900px/300px = 3)
                    $new_width = 900;
                    $new_height = 300;
                    $shift_x = 0; // No need to crop horizontally
                    $shift_y = 0; // No need to crop vertically
                }

                $src = imagecreatefromstring(file_get_contents($filename));
                $dst = imagecreatetruecolor(900, 300);
                imagecopyresampled($dst, $src, 0, 0, $shift_x, $shift_y, $new_width, $new_height, $size[0], $size[1]);
                imagedestroy($src); // Free up memory
                imagejpeg($dst, $filename, 100); // adjust format as needed
                imagedestroy($dst);
            } // end Input::hasfile('image')

            // Update published status
            $old_status = $post->is_published;
            $new_status = Input::get('published');
            if ($new_status !== $old_status) { // If change in status
                $post->is_published = $new_status;
                if ($new_status == 1) { // If post is published
                    $post->published_at = Carbon::now();
                } else { // If post is unpublished
                    $post->published_at = NULL;
                }
            }

            $new_tags = Input::get('tags');

            if (!empty($post->tags->first()->id)) {
                foreach ($post->tags as $tag) {
                    $old_tags[] = $tag->id;
                }
            } else {
                $old_tags = '';
            }

            $post->save();

            //update tags pivot table
            if ($post->save()) {
                if ($new_tags != $old_tags) {
                    $post->tags()->detach();

                    $post_id = $post->id;
                    $post = Post::find($post_id);
                    if (Input::has('tags')) {
                        foreach ($new_tags as $tag) {
                            $post->tags()->attach($tag);
                        }
                    } //end if
                } //end if
            } //end if

            Session::flash('success', 'Successfully updated post');
            return Redirect::to('admin/post');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        $filename = $post->image_path;

        // Delete the image
        if (file_exists($filename)) {
            unlink($filename);
        }

        // Delete reference in pivot table
        $post->tags()->detach();

        $post->delete();

        Session::flash('success', 'Successfully deleted the post!');
        return redirect::to('admin/post');
    }
}