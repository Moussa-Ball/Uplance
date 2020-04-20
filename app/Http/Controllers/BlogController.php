<?php

namespace App\Http\Controllers;

use App\Tag;
use App\Article;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\SEOTools;
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

        SEOMeta::setTitle('Blog - Uplance');
        SEOMeta::setDescription('Discover the latest news on uplance and trends in freelance.');
        SEOTools::setCanonical(route('blog'));

        SEOTools::opengraph()->setType('article');
        SEOTools::opengraph()->setTitle('Blog - Uplance');
        SEOTools::opengraph()->addImage(asset('images/uplance.png'));
        SEOTools::opengraph()->setSiteName('UplanceHQ');
        SEOTools::opengraph()->setDescription('Discover the latest news on uplance and trends in freelance.');
        SEOTools::opengraph()->setUrl(route('blog'));

        SEOTools::twitter()->setType('summary');
        SEOTools::twitter()->setTitle('Blog - Uplance');
        SEOTools::twitter()->setImages(asset('images/uplance.png'));
        SEOTools::twitter()->setSite('@UplanceHQ');
        SEOTools::twitter()->setDescription('Discover the latest news on uplance and trends in freelance.');
        SEOTools::twitter()->setUrl(route('blog'));

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

        SEOMeta::setTitle("Explore all articles in {$tag->name} - Uplance");
        SEOMeta::setDescription('Discover the latest news on uplance and trends in freelance.');
        SEOTools::setCanonical(route('blog.tag', $slug));

        SEOTools::opengraph()->setType('article');
        SEOTools::opengraph()->setTitle("Explore all articles in {$tag->name} - Uplance");
        SEOTools::opengraph()->addImage(asset('images/uplance.png'));
        SEOTools::opengraph()->setSiteName('UplanceHQ');
        SEOTools::opengraph()->setDescription('Discover the latest news on uplance and trends in freelance.');
        SEOTools::opengraph()->setUrl(route('blog.tag', $slug));

        SEOTools::twitter()->setType('summary');
        SEOTools::twitter()->setTitle("Explore all articles in {$tag->name} - Uplance");
        SEOTools::twitter()->setImages(asset('images/uplance.png'));
        SEOTools::twitter()->setSite('@UplanceHQ');
        SEOTools::twitter()->setDescription('Discover the latest news on uplance and trends in freelance.');
        SEOTools::twitter()->setUrl(route('blog.tag', $slug));

        return view('blog.tag', compact('articles', 'tags', 'tag', 'trendings'));
    }

    public function read($slug, $id)
    {

        $article = Article::where(['id' => $id, 'slug' => $slug])->first();
        if (!$article) return abort(404);

        $article->see += 1;
        $article->save();
        $tags = Tag::all();

        $trendings = Article::orderBy('see', 'DESC')->limit(3)->get();

        SEOMeta::setTitle("{$article->name} - Uplance");
        SEOTools::addImages($article->image);
        SEOTools::setCanonical(route('blog.read', ['slug' => $slug, 'id' => $id]));

        SEOTools::opengraph()->setType('article');
        SEOTools::opengraph()->setTitle("{$article->name} - Uplance");
        SEOTools::opengraph()->addImage($article->image);
        SEOTools::opengraph()->setSiteName('UplanceHQ');
        SEOTools::opengraph()->setUrl(route('blog.read', ['slug' => $slug, 'id' => $id]));

        SEOTools::twitter()->setType('summary');
        SEOTools::twitter()->setTitle("{$article->name} - Uplance");
        SEOTools::twitter()->setImages($article->image);
        SEOTools::twitter()->setSite('@UplanceHQ');
        SEOTools::twitter()->setUrl(route('blog.read', ['slug' => $slug, 'id' => $id]));

        return view('blog.read', compact('article', 'tags', 'trendings'));
    }

    public function search(Request $request)
    {
        $search = ($request->get('q')) ? $request->get('q') : '*';
        $articles = Article::search($search)
            ->orderBy('created_at', 'DESC')->paginate(5);

        $trendings = Article::orderBy('see', 'DESC')->limit(3)->get();
        $tags = Tag::all();

        SEOMeta::setTitle("Your searched for {$search} - Uplance");
        SEOTools::addImages(asset('images/uplance.png'));
        SEOTools::setCanonical(route('blog.search', ['q' => $search]));

        SEOTools::opengraph()->setType('article');
        SEOTools::opengraph()->setTitle("Your searched for {$search} - Uplance");
        SEOTools::opengraph()->setSiteName('UplanceHQ');
        SEOTools::opengraph()->setUrl(route('blog.search', ['q' => $search]));

        SEOTools::twitter()->setType('summary');
        SEOTools::twitter()->setTitle("Your searched for {$search} - Uplance");
        SEOTools::twitter()->setSite('@UplanceHQ');
        SEOTools::twitter()->setUrl(route('blog.search', ['q' => $search]));

        return view('blog.search', compact('articles', 'tags', 'trendings'));
    }
}
