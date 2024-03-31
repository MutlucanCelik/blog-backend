<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function show(){
        $categories = Category::all();

        return response()->json($categories);
    }
    public function create(Request $request){

        $validator = Validator::make($request->all(),[
            'name' => 'required|string|unique:categories',
            'image' => 'required|mimes:jpg,jpeg,png|max:2048',
        ],[
            'name:required' => 'Kategori adı alanı boş geçilemez',
            'name.unique' => 'Bu isimde zaten bir kategori mevcut',
            'image:required' => 'Resim alanı boş geçilemez',
            'image.mimes' => 'JPEG, JPG veya PNG uzantılı dosyalar yüklenebilir',
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

            $category = Category::create($data);

            return response()->json(['message' => 'category created'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }



}
