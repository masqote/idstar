<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\News;
use App\Comment;
use Illuminate\Support\Facades\Auth;
use Validator;
use DB;


class NewsController extends Controller
{
	public $successStatus = 200;

    public function index()
    {
    	$news = DB::table('news')->paginate(3);

    	return response()->json(['success' => $news]);
    }

    public function store(Request $request)
    {
    	$validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'thumbnail' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);            
        }

        // $input = $request->all();
        News::create($request->all());

        return response()->json('success', $this->successStatus);
    }

    public function edit($id)
    {
    	$news = News::find($id);

    	return response()->json(['success' => $news], $this->successStatus);
    }

    public function update(Request $request,$id)
    {
    	$news = News::findOrFail($id);
    	$news->update($request->all());
    	return response()->json(['success' => $news], $this->successStatus);
    }

    public function delete($id)
    {
    	$news = News::find($id)->delete();

    	return response()->json(['Success delete'], $this->successStatus);
    }

    public function comment(Request $request, $id)
    {
    	$validator = Validator::make($request->all(), [
            'isi_comment' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);            
        }

    	$comment = Comment::create($request->all());
    	return response()->json(['success' => $comment], $this->successStatus);

    }

    public function newsDetail(Request $request, $id)
    {
    	$news = News::find($id);
    	$comment = DB::select("
    		SELECT u.name,c.isi_comment FROM comments as c 
				JOIN users as u ON c.users_id = u.id
				JOIN news as n ON c.news_id = n.id
			WHERE n.id = $id
    		");
    	$news['comment'] = $comment;
    	return response()->json([
    		'news' => $news,
    	]);
    }
}
