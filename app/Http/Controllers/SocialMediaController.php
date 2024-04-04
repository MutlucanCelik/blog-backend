<?php

namespace App\Http\Controllers;

use App\Models\SocialMedia;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SocialMediaController extends Controller
{
    public function getSocialMedia(Request $request){
        $socialMedia = SocialMedia::where('user_id',$request->user_id)->get();

        try {
            if($socialMedia){
                return response()->json(['social_media' => $socialMedia],200);
            }else{
                return response()->json(['error' => 'Social media not found'],404);
            }
        }catch (\Exception $e){
            return response()->json(['errors' => $e->getMessage()],500);
        }
    }

    public function create(Request $request){
        $user = User::where('id',$request->user_id)->first();

        $validator = Validator::make($request->all(),[
            'user_id' => 'required',
            'order' => 'integer|nullable',
            'name' => 'required|string',
            'icon' => 'required|mimes:jpg,jpeg,png,svg|max:2048',
            'link' => 'required|string'
        ],[
            'user_id.required' => 'Kullanıcı kimliğiniz gelmedi tekrar deneyin',
            'order.integer' => 'Girilen değer sayı olmalıdır',
            'name.required' => 'İsim alanı boş geçilemez',
            'icon.required' => 'İcon alanı zorunludur',
            'icon.mimes' => 'JPG, JPEG, PNG veya SVG türünde dosyalar yükleyebilirsiniz',
            'icon.max' => 'En fazla 2MB boyutunda resimler yükleyebilirsiniz',
            'link.required' => 'Link alanı boş bırakılamaz'
        ]);

        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()],400);
        }

        try {
            if($user){
                $data = [
                    'user_id' => $request->user_id,
                    'order' => isset($request->order) ? $request->order : 0,
                    'name' => $request->name,
                    'link' => $request->link
                ];
                if($request->hasFile('icon')){
                    $file = $request->file('icon');
                    $data['icon'] = Storage::url($file->store('public/social-media'));
                }

                SocialMedia::create($data);
                return  response()->json(['success' => 'Social medial created'],200);
            }else{
                return response()->json(['error' => 'User not found'],404);
            }


        }catch (\Exception $e){
            return response()->json(['errors' => $e->getMessage()],500);
        }
    }

    public function update(Request $request){
        $socialMedia = SocialMedia::where('id',$request->id)->first();

        $validator = Validator::make($request->all(),[
            'order' => 'integer|nullable',
            'name' => 'nullable|string',
            'icon' => 'nullable|mimes:jpg,jpeg,png,svg|max:2048',
            'link' => 'nullable|string'
        ],[
            'order.integer' => 'Girilen değer sayı olmalıdır',
            'name.required' => 'İsim alanı boş geçilemez',
            'icon.mimes' => 'JPG, JPEG, PNG veya SVG türünde dosyalar yükleyebilirsiniz',
            'icon.max' => 'En fazla 2MB boyutunda resimler yükleyebilirsiniz',
            'link.required' => 'Link alanı boş bırakılamaz'
        ]);

        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()],400);
        }

        try {
            if($socialMedia){
                $socialMedia->order = isset($request->order) ? $request->order : 0;
                $socialMedia->name = isset($request->name) ? $request->name : $socialMedia->name;
                $socialMedia->link = isset($request->link) ? $request->link : $socialMedia->link;

                if($request->hasFile('icon')){
                    $file = $request->file('icon');
                    $socialMedia->icon = Storage::url($file->store('public/social-media'));
                }

                $socialMedia->save();

                return  response()->json(['social_media' => $socialMedia],200);
            }else{
                return response()->json(['error' => 'Social media not found'],404);
            }

        }catch (\Exception $e){
            return response()->json(['errors' => $e->getMessage()],500);
        }
    }

    public function delete(Request $request){
        $socialMedia = SocialMedia::where('id',$request->id)->first();

        try {
            if($socialMedia){
                $socialMedia->delete();
                return response()->json(['success' => 'Social media deleted'],204);
            }else{
                return response()->json(['error' => 'Article not found'], 404);
            }
        }catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 500);
        }
    }
}
