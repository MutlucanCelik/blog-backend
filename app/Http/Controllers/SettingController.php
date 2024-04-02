<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    public function getSettings(){
        $settings = Setting::all();

        try {
            if($settings){
                return response()->json(['settings' => $settings],200);
            }else{
                return response()->json(['error' => 'Setting not found'],404);
            }
        }catch (\Exception $e){
            return  response()->json(['erros' => $e->getMessage()],500);
        }
    }

    public function update(Request $request){
        $setting = Setting::first();

        $validator = Validator::make($request->all(),[
            'logo_image' => 'mimes:jpg,jpeg,png|max:2048',
            'home_slider_image' => 'mimes:jpg,jpeg,png|max:2048',
        ],[
            'logo_image.mimes' => 'JPG, JPEG ve PNG uzantılı dosyalar yüklenebilir.',
            'logo_image.max' => 'En fazla 2MB büyüklüğünde resimler yüklenebilir.',
            'home_slider_image.mimes' => 'JPG, JPEG ve PNG uzantılı dosyalar yüklenebilir.',
            'home_slider_image.max' => 'En fazla 2MB büyüklüğünde resimler yüklenebilir.'
        ]);

        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()],400);
        }

        try {
            if($setting){
                if($request->hasFile('logo_image')){
                    $setting->logo_image = $request->logo_image;
                }
                $setting->footer_text = isset($request->footer_text ) ? $request->footer_text : $setting->footer_text;
                $setting->description = isset($request->description) ? $request->description : $setting->description;
                if($request->hasFile('home_slider_image')){
                    $setting->home_slider_image = $request->home_slider_image;
                }
                $setting->home_slider_text = isset($request->home_slider_text) ? $request->home_slider_text : $setting->home_slider_text;
                $setting->save();

                return response()->json(['settings' => $setting],200);
            }else{
                return  response()->json(['error' => 'Setting not found'],404);
            }
        }catch(\Exception $e){
            response()->json(['errors' => $e->getMessage()],500);
        }
    }
}
