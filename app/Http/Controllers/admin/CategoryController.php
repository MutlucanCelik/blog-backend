<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function getAll(){
        $categories = Category::all();

        try {
            if($categories){
                return response()->json(['categories' => $categories],200);
            }else{
                return response()->json(['error' => 'There are no categories registered'],404);
            }

        }catch(\Exception $e) {
            return response()->json(['errors' => $e->getMessage()],400);
        }
    }

    public function getAllArticlesByCategory(Request $request){
        try {
            $category = Category::with('getArticles')->where('id',$request->id)->first();

            if($category){
                return response()->json(['category' => $category],200);
            }else{
                return  response()->json(['error' => 'Category not found'],404);
            }
        }catch (\Exception $e){
            return response()->json(['errors' => $e->getMessage()],400);
        }
    }

    public function create(Request $request){

        $validator = Validator::make($request->all(),[
            'name' => 'required|string|unique:categories',
            'image' => 'required|mimes:jpg,jpeg,png|max:2048',
        ],[
            'name.required' => 'Kategori adı alanı boş geçilemez',
            'name.unique' => 'Bu isimde zaten bir kategori mevcut',
            'image.required' => 'Resim alanı boş geçilemez',
            'image.mimes' => 'JPEG, JPG veya PNG uzantılı dosyalar yüklenebilir',
            'image.max' => 'Maksimum 2MB boyutunda dosya yüklenebilir'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            $data = [
                'parent_id' => $request->parent_id ?? null,
                'name' => $request->name,
                'image' => $request->image,
                'order' => isset($request->order) ? 1 : 0,
                'show_home_page_status' => isset($request->show_home_page_status) ? 1 : 0,
            ];

            Category::create($data);

            return response()->json(['success' => 'category created'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request){
        $category = Category::where('id',$request->id)->first();

        $validator = Validator::make($request->all(),[
            'name' => 'required|string|unique:categories',
            'image' => 'required|mimes:jpg,jpeg,png|max:2048',

        ],[
            'name.required' => 'Kategori adı alanı boş geçilemez',
            'name.unique' => 'Bu isimde zaten bir kategori mevcut',
            'image.required' => 'Resim alanı boş geçilemez',
            'image.mimes' => 'JPEG, JPG veya PNG uzantılı dosyalar yüklenebilir',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            $category->parent_id = $request->parent_id;
            $category->order = $request->order ?? 0;
            $category->name = $request->name;
            $category->image = $request->image;
            $category->status = isset($request->status) ? 1 : 0;
            $category->show_home_page_status = isset($request->show_home_page_status) ? 1 : 0;
            $category->save();

            return response()->json(['category'=> $category],200);
        }catch (\Exception $e){
            return response()->json(['errors' => $validator->errors()], 500);
        }

    }

    public function delete(Request $request){
        try {
            $category = Category::where('id',$request->id)->first();
            if($category){
                $category->delete();
                return response()->json(['success' => 'category deleted'],204);
            }else{
                return response()->json(['error' => 'Category not found'], 404);
            }

        }catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 500);
        }
    }

    public function getByDetail(Request $request){
        try {
            $category = Category::where('id',$request->id)->first();
            if($category){
                return  response()->json(['category' => $category],200);
            }else{
                return  response()->json(['error' => 'Category not found'],404);
            }
        }catch (\Exception $e){
            return response()->json(['errors' => $e->getMessage()],500);
        }
    }

}
