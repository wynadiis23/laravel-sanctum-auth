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
    /**
     * show article by id
     */
    public function show($id)
    {
        try{
            $article = Article::findOrFail($id);
            /**
             * bagian ini tidak bekerja
             * laravel langsung route ke page 404ketika tidak ada data sesuai id
             * edit di bagian app\Exceptions\Handler.php 
             */
            if($article === null) {
                return response()->json([
                    'success' => false,
                    'message' => 'Article not found with this id',
                    'data' => $article
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Article found, here it is',
                'data' => $article
            ]);

        } catch(Exception $error) {
            return response()->json([
                'message' => 'Error in finding article',
                'error' => $error,
            ]);
        }
    }

    /**
     * update function untuk artikel 
     */
    public function update(Request $request, $id)
    {   
        try{
            $request->validate([
                'title' => 'required',
                'author' => 'required'
            ]);
            //find article by id
            $article = Article::findOrFail($id);

            //update article properties
            $article->title = $request->title;
            $article->author = $request->author;
            
            //savearticle
            if($article->save()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Article data successfully updated',
                    'data' => $article
                ]);
            }
            

        } catch(Exception $error) {
            return response()->json([
                'message' => 'Error in update article',
                'error' => $error,
            ]);
        }
    }

    /**
     * delete data article
     */
    public function destroy($id)
    {
        try{
            $article = Article::findOrFail($id);

            if($article->delete()){
                return response()->json([
                    'success' => true,
                    'message' => 'Article data successfully deleted'
                ]);
            }
        } catch(Exception $error) {
            return response()->json([
                'message' => 'Error in deleting article',
                'error' => $error,
            ]);
        }
    }
}
