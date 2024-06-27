<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index()
    {
        return view('articles.index');
    }

    public function loadMore(Request $request)
    {
        if ($request->ajax()) {
            $articles = Article::latest()->skip($request->skip)->take(5)->get();
            return response()->json($articles);
        }
    }
}
