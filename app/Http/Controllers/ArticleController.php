<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;

class ArticleController extends Controller
{
    /**
     * return all article
     */
    public function index()
    {
        $articles = Article::all();

        if (empty($articles)) {
            return response()->json([
                'success' => true,
                'message' => 'There is no articles',
                'data' => $articles
            ]);
        } else {
            return response()->json([
                'success' => true,
                'message' => 'Here are list of articles',
                'data' => $articles
            ]);
        }
    }
    /**
     * store a article
     */
    public function store(Request $request) 
    {
        try{
            $request->validate([
                'title' => 'required',
                'author' => 'required'
            ]);

            $article = Article::create([
                'title' => $request->title,
                'author' => $request->author
            ]);

            if ($article) {
                return response()->json([
                    'success' => true,
                    'message' => 'Article successfully created',
                    'data' => $article
                ]);
            }
        } catch(Exception $error) {
            return response()->json([
                'message' => 'Error in adding article',
                'error' => $error,
            ]);
        }
    }
}
