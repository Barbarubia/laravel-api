<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Post;
use App\Category;
use App\Tag;
use App\User;

class PostController extends Controller
{
    use \App\Traits\postsFilters;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $attributes = $request->all();

        if (array_key_exists('home', $attributes)) {
            return response()->json([
                'success'   => true,
                'results'   => [
                    'data'  => Post::with(['user', 'category', 'tags'])->orderBy('id', 'desc')->limit(5)->get(),
                ]
            ]);
        }

        $posts = $this->composeQuery($request);

        $posts = $posts->with(['user', 'category', 'tags'])->paginate(10);

        $queries = $request->query();
        unset($queries['page']);
        $posts->withPath('?' . http_build_query($queries, '', '&'));

        $categories = Category::all();

        $users = User::all();

        $tags = Tag::all();

	    return response()->json([
            'success'       => true,
            'results'       => $posts,
            'categories'    => $categories,
            'users'         => $users,
            'tags'          => $tags,
	    ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $post = Post::with(['user', 'category', 'tags'])->where('slug', $slug)->first();

        if ($post) {
            $post->url_image = asset('storage/' . $post->post_image);
            return response()->json([
                'success'   => true,
                'results'   => [
                    'data'  => $post,
                ]
            ]);
        } else {
            return response()->json([
                'success'   => false,
            ]);
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
