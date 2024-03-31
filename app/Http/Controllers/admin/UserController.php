<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function getAll(){
        $users = User::all();

        try {
            if($users){
                return response()->json(['users'=>$users],200);
            }else{
                return response()->json(['error' =>'No registered users'],404);
            }
        }catch (\Exception $e){
            return response()->json(['errors' => $e->getMessage()],500);
        }
    }

    public function getByDetail(Request $request){
        $user = User::where('id',$request->id)->first();

        try {
            if($user){
                return response()->json(['user' => $user],200);
            }else{
                return response()->json(['error' => 'User not found'],404);
            }
        }catch (\Exception $e){
            return response()->json(['errors' => $e->getMessage()],500);
        }
    }

    public function create(Request $request){

        try {
            $validator = Validator::make($request->all(),[
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6|regex:/^.*(?=.{7,})(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            ],[
                'email.email' => 'Email formatında bir değer giriniz',
                'email.required' => 'Email alanı zorunludur.',
                'email.unique' => 'Bu email zaten kullanılmaktadır',
                'first_name.required' => 'Ad alanı zorunludur.',
                'last_name.required' => 'Soyad alanı zorunludur.',
                'password.required' => 'Şifre alanı zorunludur.',
                'password.min' => 'Şifre alanı en az 8 karaktererden oluşmaldıır',
                'password.regex' => 'Şifreniz bir büyük ve bir küçük harf içermelidir'
            ]);

            if($validator->fails()){
                return response()->json(['errors' => $validator->errors()],400);
            }

            $data = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ];

            User::create($data);
            return response()->json(['success' => 'user created'], 201);

        }catch (\Exception $e){
            return response()->json(['errors' => $e->getMessage()],500);
        }
    }

    public function update(Request $request){
        $user = User::where('id',$request->user_id)->first();

        try {
            $validator = Validator::make($request->all(),[
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'email' => 'required|email|unique:users',
                'image' => 'required|image|mimes:jpg,jpeg,png|max:2048'
            ],[
                'first_name.required' => 'Ad alanı zorunludur.',
                'last_name.required' => 'Soyad alanı zorunludur.',
                'email.email' => 'Email formatında bir değer giriniz',
                'email.required' => 'Email alanı zorunludur.',
                'email.unique' => 'Bu email zaten kullanılmaktadır',
                'image.required' => 'Resim alanı boş geçilemez',
                'image.mimes' => 'JPEG, JPG veya PNG uzantılı dosyalar yüklenebilir',
                'image.max' => 'Maksimum 2MB boyutunda dosya yüklenebilir'

            ]);

            if($validator->fails()){
                return response()->json(['errors' => $validator->errors()],400);
            }

            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->image = $request->image;
            $user->save();

            return response()->json(['user' => $user], 200);

        }catch (\Exception $e){
            return response()->json(['errors' => $e->getMessage()],500);
        }
    }

    public function changePassword(Request $request)
    {
        $user = User::where('id',$request->user_id)->first();

        if(!$user) {
            return response()->json(['error' => 'Kullanıcı bulunamadı'], 404);
        }

        if(!Hash::check($request->password, $user->password)) {
            return response()->json(['error'=> 'Mevcut şifre hatalı'],404);
        }

        if($request->new_password !== $request->confirm_password) {
            return response()->json(['error' => 'Şifreler eşleşmiyor'], 400);
        }

        if(Hash::check($request->new_password, $user->password)){
            return response()->json(['error' => 'Yeni şifre eski şifre ile aynı olamaz'], 400);
        }

        $validator = Validator::make($request->all(),[
            'new_password' => 'required|min:6|regex:/^.*(?=.{6,})(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
        ],[
            'new_password.required' => 'Şifre alanı zorunludur',
            'new_password.min' => 'Şifre alanı en az 8 karaktererden oluşmaldıır',
            'new_password.regex' => 'Şifreniz bir büyük ve bir küçük harf içermelidir'
        ]);

        if($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            $user->password = Hash::make($request->new_password);
            $user->save();

            return response()->json(['success' => 'Şifre güncellendi'], 200);
        } catch (\Exception $e) {
            return  response()->json(['errors' => $e->getMessage()], 500);
        }
    }

    public function changeStatus(Request $request){
        $user = User::where('id',$request->user_id)->first();

        if(!$user) {
            return response()->json(['error' => 'Kullanıcı bulunamadı'], 404);
        }

        try {
            $user->status = !$user->status;
            $user->save();

            return response()->json(['success'=>'Status updated'],200);

        }catch (\Exception $e){
            return  response()->json(['errors' => $e->getMessage()], 500);
        }
    }
}
