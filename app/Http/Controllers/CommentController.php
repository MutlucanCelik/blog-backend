<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\UserLikeComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function create(Request $request){
        $validator = Validator::make($request->all(),[
            'user_id' => 'required|integer',
            'article_id' => 'required|integer',
            'comment' => 'required',

        ],[
            'user_id.required' => 'Kullanıcı kimliğinize erişelemedi sayfayı yenileyip tekrar deneyin',
            'article_id' => 'Yorum tapılacak makalenin kimliğine erişelemedi lütfen tekrar deneyin',
            'comment.required' => 'Mesaj alanı boş bırakılmaz'
        ]);

        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()],400);
        }

        try {
            $data = [
                'parent_id' => $request->parent_id,
                'user_id' => $request->user_id,
                'article_id' => $request->article_id,
                'comment' => $request->comment
            ];

            Comment::create($data);
            return response()->json(['success' => 'Comment created'],200);

        }catch (\Exception $e){
            return response()->json(['errors',$e->getMessage()],500);
        }
    }

    public function update(Request $request){

    }

    public function delete(Request $request){
        $comment = Comment::where('id',$request->id)->first();
        $subComments = Comment::where('parent_id',$request->id)->get();

        try {
            if($comment){
                $comment->delete();
                foreach ($subComments as $subComment) {
                    $subComment->delete();
                }
                return response()->json(['success' => 'Comment deleted'],204);
            }else{
                return response()->json(['error' => 'Comment not found'],404);
            }

        }catch (\Exception $e){
            return response()->json(['errors' => $e->getMessage()],500);
        }
    }

}
