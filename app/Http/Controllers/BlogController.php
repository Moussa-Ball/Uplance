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

        if ($articles->isEmpty())
            return view('blog.empty');

        $trendings = Article::orderBy('see', 'DESC')->limit(3)->get();
        $tags = Tag::all();
        return view('blog.index', compact('articles', 'tags', 'trendings'));
    }

    public function tag($slug)
    {
        $tag = Tag::where('slug', $slug)->first();
        if (!$tag)
            return abort(404);

        $articles = $tag->articles()->paginate(5);
        if ($articles->isEmpty())
            return view('blog.empty');

        $trendings = Article::orderBy('see', 'DESC')->limit(3)->get();
        $tags = Tag::all();
        return view('blog.tag', compact('articles', 'tags', 'tag', 'trendings'));
    }

    public function read(Article $article)
    {
        $article->see += 1;
        $article->save();
        $tags = Tag::all();
        $trendings = Article::orderBy('see', 'DESC')->limit(3)->get();
        return view('blog.read', compact('article', 'tags', 'trendings'));
    }

    public function search(Request $request)
    {
        $search = ($request->get('q')) ? $request->get('q') : '*';
        $articles = Article::search($search)
            ->orderBy('created_at', 'DESC')->paginate(5);

        $trendings = Article::orderBy('see', 'DESC')->limit(3)->get();
        $tags = Tag::all();
        return view('blog.search', compact('articles', 'tags', 'trendings'));
    }
}
