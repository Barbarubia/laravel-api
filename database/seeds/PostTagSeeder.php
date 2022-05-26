<?php

use Illuminate\Database\Seeder;
use App\Post;
use App\Tag;

class PostTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Richiamo tutti i post già presenti nel DB
        $posts = Post::all();

        foreach ($posts as $post) {
            // Ad ogni post assegno randomicamente un numero di tags tra 0 e 3 e collego il relativo id
            $postTags = Tag::inRandomOrder()->limit(rand(0, 3))->get();
            $post->tags()->attach($postTags->pluck('id')->all());
        }
    }
}
