<?php

namespace App\Http\Controllers;

use App\Models\UserLikeArticle;
use App\Models\UserLikeComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserLikesController extends Controller
{
    public function articleLikeStatus(Request $request){
        $validator = Validator::make($request->all(),[
            'user_id' => 'required',
            'article_id' => 'required'
        ],[
            'user_id.required' => 'Kullanıcı kimliğinde sorun çıktı tekrar edeniyin',
            'article_id.required' => 'Makale kimliğinde sorun çıktı tekrar edeniyin',
        ]);

        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()],400);
        }

        $article = UserLikeArticle::where('article_id',$request->article_id)->where('user_id',$request->user_id)->first();
        if($article){
            $article->delete();
            return response()->json(['success','Article disliked'],204);
        }

        $data = [
            'user_id' => $request->user_id,
            'article_id' => $request->article_id
        ];

        UserLikeArticle::create($data);
        return response()->json(['success' => 'Article liked'],204);
    }

    public function commentLikeStatus(Request $request){
        $validator = Validator::make($request->all(),[
            'user_id' => 'required',
            'comment_id' => 'required'
        ],[
            'user_id.required' => 'Kullanıcı kimliğinde sorun çıktı tekrar edeniyin',
            'comment_id.required' => 'Yorum kimliğinde sorun çıktı tekrar edeniyin',
        ]);

        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()],400);
        }

        $comment = UserLikeComment::where('comment_id',$request->comment_id)->where('user_id',$request->user_id)->first();
        if($comment){
            $comment->delete();
            return response()->json(['success','Comment disliked'],204);
        }

        $data = [
            'user_id' => $request->user_id,
            'comment_id' => $request->comment_id
        ];

        UserLikeComment::create($data);
        return response()->json(['success' => 'Comment liked'],204);
    }
}
