<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Post;
use App\User;
use App\Category;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    use \App\Traits\postsFilters;

    protected $validationParameters = [
        'title'         => 'required|max:100',
        'slug'          => 'required|unique:posts|max:105',
        // 'user_id'       => 'required|exists:App\User,id',
        'category_id'   => 'required|exists:App\Category,id|max:30',
        'image'         => 'nullable|url|max:250',
        'content'       => 'required',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $posts= $this->composeQuery($request);

        $posts = $posts->paginate(25);

        $queries = $request->query();
        unset($queries['page']);
        $posts->withPath('?' . http_build_query($queries, '', '&'));

        $categories = Category::all();

        $users = User::all();

        return view('admin.posts.index', [
            'posts'         => $posts,
            'categories'    => $categories,
            'users'         => $users,
            'request'       => $request
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();

        return  view('admin.posts.create', [
            'categories'    => $categories,
            'tags'          => $tags
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validazione dei dati inseriti
        $request->validate($this->validationParameters);

        $data = $request->all();

        $img_path = Storage::put('uploads', $data['post_image']);

        // Variabile inputForm per richiedere tutti i dati inseriti nel form della pagina posts.create
        $inputForm = [
            'user_id'       => Auth::user()->id,
            'post_image'    => $img_path
        ] + $data;

        // Attribuzione tags gi?? esistenti o generazione nuovi tags se non esistenti
        preg_match_all('/#(\S*)/', $inputForm['tags'], $newTags);

        $tagIds = [];

        foreach ($newTags[1] as $tag) {
            $newTag = Tag::firstOrCreate([
                'name' => $tag,
                'slug'  => Str::slug($tag)
            ]);

            $tagIds[] = $newTag->id;
        }

        $inputForm['tags'] = $tagIds;

        // Creazione della nuova riga nel database con i dati inseriti nel form
        $newPost = Post::create($inputForm);
        $newPost->tags()->attach($inputForm['tags']);

        // Redirect al post creato
        return redirect()->route('admin.posts.show', $newPost->slug)->with('created', 'Post created with success!');;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        // Ogni user pu?? modificare solo i propri posts
        if (Auth::user()->id !== $post->user_id) abort(403);

        $categories = Category::all();
        $tags = Tag::all();

        return view('admin.posts.edit', compact('post', 'categories', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        // Ogni user pu?? aggiornare solo i propri posts
        if (Auth::user()->id !== $post->user_id) abort(403);

        // Validazione dei dati del form modificati ignorando la propriet?? "unique" dello slug solo per la risorsa selezionata
        $this->validationParameters['slug'] = [
            'required',
            Rule::unique('posts')->ignore($post),
            'max:105'
        ];

        $request->validate($this->validationParameters);

        // Modifica dei dati nel database
        $inputForm = $request->all();

        // Attribuzione tags gi?? esistenti o generazione nuovi tags se non esistenti
        preg_match_all('/#(\S*)/', $inputForm['tags'], $newTags);

        $tagIds = [];

        foreach ($newTags[1] as $tag) {
            // Sostituito firstOrCreate con updateOrCreate (con il primo mi creava una riga vuota nella tabella dei tags)
            $newTag = Tag::updateOrCreate([
                'name' => $tag,
                'slug'  => Str::slug($tag)
            ]);

            $tagIds[] = $newTag->id;
        }

        $inputForm['tags'] = $tagIds;

        if (array_key_exists('post_image', $inputForm)) {
            Storage::delete($post->post_image);
            $img_path = Storage::put('uploads', $inputForm['post_image']);
            $inputForm = [
                'post_image' => $img_path
            ] + $inputForm;
        }

        $post->update($inputForm);
        // prima cancello i tags gi?? presenti
        $post->tags()->detach();
        // e poi li riaggiorno con quelli inseriti nel form di edit, in questo modo non creo doppioni di tag sullo stesso post
        $post->tags()->attach($inputForm['tags']);

        // Reindirizzamento alla pagina del post
        return redirect()->route('admin.posts.show', $post->slug)->with('modified', 'Post modified with success!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        // Ogni user pu?? eliminare solo i propri posts
        if (Auth::user()->id !== $post->user_id) abort(403);

        // Prima di eliminare il post dalla tabella posts, lo elimino dalla tabella pivot usando il metodo detach
        $post->tags()->detach();

        $post->delete();

        return redirect()->route('admin.posts.index')->with('deleted', 'Post #' . $post->id . ' deleted with success!');;
    }
}
