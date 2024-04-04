<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    public function create(Request $request){

        $validator = Validator::make($request->all(),[
            'category_id' =>'required|integer',
            'user_id' => 'required|integer',
            'title' => 'required|string',
            'body' => 'required|string',
            'image' =>'required|mimes:jpg,jpeg,png|max:2048',
        ],[
            'category_id.required' => 'Kategori alanı boş geçilemez',
            'title.required' => 'Başlık alanı boş geçilemez',
            'body:required' => 'İçerik alanı boş geçilemez',
            'image.required' => 'Resim alanı boş geçilemez',
            'image.mimes' => 'JPEG, JPG veya PNG uzantılı dosyalar yüklenebilir',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            $wordCount = count(explode(' ', $request->body));
            $averageReadingSpeed = 150; // Ortalama okuma hızı 150 kelime dedik

            $data = [
                'category_id' => $request->category_id,
                'user_id' => $request->user_id,
                'title' => $request->title,
                'body' => $request->body,
                'status' => isset($request->status) ? 1 : 0,
                'reading_time' => ceil($wordCount / $averageReadingSpeed), //dakikada 200 kelime dedik
                'publish_date' => $request->publish_date
            ];

            if($request->hasFile('image')){
                $file = $request->file('image');
                $data['image'] = Storage::url($file->store('public/article'));
            }

            Article::create($data);

            return response()->json(['success' => 'article created'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request){
        $article = Article::where('id',$request->id)->first();

        $validator = Validator::make($request->all(),[
            'category_id' =>'required|integer',
            'title' => 'required|string',
            'body' => 'required|string',
            'image' =>'required|mimes:jpg,jpeg,png|max:2048',
        ],[
            'category_id.required' => 'Kategori alanı boş geçilemez',
            'title.required' => 'Başlık alanı boş geçilemez',
            'body:required' => 'İçerik alanı boş geçilemez',
            'image.required' => 'Resim alanı boş geçilemez',
            'image.mimes' => 'JPEG, JPG veya PNG uzantılı dosyalar yüklenebilir',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            $wordCount = count(explode(' ', $request->body));
            $averageReadingSpeed = 150; // Ortalama okuma hızı 150 kelime dedik

            $article->category_id = $request->category_id;
            $article->title = $request->title;
            $article->body = $request->body;
            $article->status = isset($request->status) ? 1 : 0;
            $article->reading_time =  ceil($wordCount / $averageReadingSpeed);
            $article->publish_date = $request->publish_date;

            if($request->hasFile('image')){
                $file = $request->file('image');
                $article->image = Storage::url($file->store('public/article'));
            }
            $article->save();

            return response()->json(['article'=> $article],200);
        }catch (\Exception $e){
            return response()->json(['errors' => $validator->errors()], 500);
        }

    }

    public function delete(Request $request){
        $article = Article::where('id',$request->id)->first();

        try {
            if($article){
                $article->delete();
                return response()->json(['success' => 'Article deleted'],204);
            }else{
                return response()->json(['error' => 'Article not found'], 404);
            }

        }catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 500);
        }
    }

    public function getByDetail(Request $request){
        try {
            $article = Article::withCount('articleLikes')
                ->with(['getComments' => function($query){
                    $query->withCount('commentLikes')
                        ->with('user');
                }])
                ->where('id', $request->id)
                ->first();

            if($article){
                return  response()->json(['article' => $article],200);
            }else{
                return  response()->json(['error' => 'Category not found'],404);
            }
        }catch (\Exception $e){
            return response()->json(['errors' => $e->getMessage()],500);
        }
    }
}
