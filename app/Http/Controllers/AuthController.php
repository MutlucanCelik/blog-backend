<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request){

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

            $user = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ];

            User::create($user);

            return response()->json(['success' => 'User registered successfully'], 201);

        }catch (\Exception $e){
            return response()->json(['errors' => $e->getMessage()],500);
        }
    }

    public function login(Request $request)
    {
        $login = $request->input('login');
        $password = $request->input('password');

        try {
            if (Auth::attempt(['email' => $login, 'password' => $password])) {
                $user = Auth::user();

                return response()->json(['user' => $user,])->setStatusCode(200);
            } else {
                return response()->json(['errors' => ['Email and password do not match']])->setStatusCode(401);
            }
        }catch (\Exception $e){
            return response()->json(['errors' => $e->getMessage()],500);
        }
    }
}
