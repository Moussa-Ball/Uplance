<?php

namespace App\Http\Controllers;

use App\Tag;
use App\Article;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $articles = Article::orderBy('created_at', 'DESC')->paginate(5);

        if (!$articles->count()) {
            return view('blog.empty');
        }

        $trendings = Article::orderBy('view', 'DESC')->limit(3);
        $tags = Tag::all();
        return view('blog.index', compact('articles', 'tags', 'trendings'));
    }

    public function tag($slug)
    {
        $tag = Tag::where('slug', $slug)->first();
        $articles = $tag->articles()->paginate(5);
        $tags = Tag::all();
        return view('blog.tag', compact('articles', 'tags', 'tag'));
    }

    public function read($slug, $id)
    {
        $article = Article::where(['slug' => $slug, 'id' => $id])->first();
        $article->see += 1;
        $article->save();
        $tags = Tag::all();
        return view('blog.read', compact('article', 'tags'));
    }

    public function search(Request $request)
    {
        $articles = Article::search($request->get('q'))
            ->orderBy('created_at', 'DESC')->paginate(5);

        if (!$articles->count()) {
            return view('blog.empty');
        }

        $trendings = Article::orderBy('view', 'DESC')->limit(3);
        $tags = Tag::all();
        return view('blog.index', compact('articles', 'tags', 'trendings'));
    }
}
