<?php

namespace App\Traits;

use App\Post;

trait postsFilters {
    function composeQuery($request) {
        $postsQuery = Post::whereRaw('1 = 1');

        // Stringa di ricerca sia nel titolo che nel contenuto del post
        if ($request->search) {
            $postsQuery->where(function($query) use ($request) {
                $query->where('title', 'LIKE', "%$request->search%")
                    ->orWhere('content', 'LIKE', "%$request->search%");
            });
        }

        if ($request->category) {
            $postsQuery->where('category_id', $request->category);
        }

        if ($request->author) {
            $postsQuery->where('user_id', $request->author);
        }

        if ($request->tags) {
            $tags = $request->tags;
            foreach ($tags as $tag) {
                $postsQuery->whereHas('tags', function($query) use ($tag) {
                    $query->where('name', $tag);
                });
            }
        }

        return $postsQuery;
    }
}
