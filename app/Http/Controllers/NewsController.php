<?php

namespace App\Http\Controllers;

use App\CN\CNNews\News;
use App\CN\CNNews\NewsRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\Validator;

class NewsController extends ApiController
{

    protected $news;

    /**
     * @param NewsRepository $message
     */
    public function __construct(NewsRepository $news ){

        //$this->auth = $auth;
        $this->news = $news ;
        $this->middleware('jwt.auth');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function retrieveNewsItem($newsId)
    {
        return News::findOrFail($newsId);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function createNews(Request $request)
    {
        return $this->news->createNews();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function deleteNews($newsId)
    {
        News::destroy($newsId);

        return "deleted news";
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function editNews($newsId)
    {
        $news = News::find($newsId);
        if(is_null($news)){
            return "not found";
        }
        return $this->update($newsId);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update($newsId)
    {
        $input = Input::all();
        $validation = Validator::make($input, News::$rules);

        if($validation->passes()){
            $news = News::find($newsId);
            $news->update($input);
            return "Updated news";
        }
    }

    /**a
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function retrieveShortNewsDesc(Request $request)
    {
        return $this->news->retrieveShortNewsDesc();

    }
}
