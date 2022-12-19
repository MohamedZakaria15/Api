<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticleColleciton;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use http\Env\Response;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles=Article::paginate(10);
        return $articles;
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
     * @param  \App\Http\Requests\StoreArticleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreArticleRequest $request)
    {
        $request->merge('user_id',auth()->user()->id);
       $Article= Article::create($request->all());
       $Article=auth()->user()->articles()->findOrFail($request->all());
       if($Article){
           return response()->json(['data'=>new ArticleResource($Article),'status' => 'success','message'=>'Article Created Successfully'],202);
       }
       else{
           return response()->json(['data'=>[],'status' => 'error','message' => 'Article not created'],405);

       }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $article)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateArticleRequest  $request
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpateArticleRequest $request, Article $article)
    {
        $article = auth()->user()->articles()->findOrFail($request->all());

       if(!$article)
       {
  return response()->json(['data'=>[],'status'=>'success','message'=>'article not found'],404);
       }
      // return response()->json(['data'=> new ArticleResource($article),'status'=>'success','message'=>'article'])

        if($article->update($request->all())){
            return response()->json(['data' => $article,'status' => 'success','message'=>'Article Updataed Successfully'],202);
        }
        else{
            return response()->json(['data' =>[],'status'=>'error','message' => 'Article not Updataed'],405);

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        if($article->delete()){
            return response()->json(['status' => 'success','message'=>'Article Deleted Successfully'],202);
        }
        else{
            return response()->json(['status'=>'error','message' => 'Article not Deleted'],405);

        }
    }

    public function user_articles(){
        $articles = auth()->user()->articles()->with('user:id,name')->paginate(10);
        return new ArticleColleciton($articles);
    }
}
