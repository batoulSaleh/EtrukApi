<?php

namespace App\Http\Controllers\api\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){
        $fields = $request->validate([
            'name_en' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed',
            'user_type'=>'required|in:1,2',
            'phone' =>'required|numeric',
            ],[
                'name_en.required'=> trans('api.required'),
                'email.required'=> trans('api.required'),
                'email.email'=> trans('api.email'),
                'email.unique'=> trans('api.unique'),
                'password.required'=> trans('api.required'),
                'password.confirmed'=> trans('api.confirm'),
                'user_type.required'=> trans('api.required'),
                'phone.required'=> trans('api.required'),
            ]);

        if($request->user_type==1){
            $fields = $request->validate([
                'gender' =>'required|in:m,f'
            ],[
                'gender.required'=> trans('api.required'),
                'gender.in'=> trans('api.exists'),
            ]);
            $user = User::create([
                'name_en' => $request['name_en'],
                'email' => $request['email'],
                'password' => bcrypt($request['password']),
                'user_type'=> $request['user_type'],
                'gender'=> $request['gender'],
                'phone'=> $request['phone'],
            ]);

        }elseif($request->user_type==2){
            $fields = $request->validate([
                'address'=>'required|string|max:200',
                'name_ar' => 'required|string',
                'image' => 'required|image|max:2048',
            ],[
                'address.required'=> trans('api.required'),
                'name_ar.required'=> trans('api.required'),
                'image.required'=> trans('api.required'),
            ]);

            if ($request->file('image')) {
                $image_path = $request->file('image')->store('api/users', 'public'); //store('name of folder', 'in folder public');
            } else {
                $image_path = null;
            }

            $user = User::create([
                'name_en' => $request['name_en'],
                'name_ar' => $request['name_ar'],
                'image' => asset('storage/' . $image_path),
                'email' => $request['email'],
                'password' => bcrypt($request['password']),
                'user_type'=> $request['user_type'],
                'address'=> $request['address'],
                'phone'=> $request['phone'],
            ]);
        }

        
        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'message'=>trans('api.register'),
            'user' => $user,
            'token' => $token
        ];

        return response($response,201);
    }

    public function login(Request $request){
        $fields = $request->validate([
            'email' => 'required|string|exists:users,email',
            'password' => 'required|string|'
        ],[
            'email.required'=> trans('api.required'),
            'password.required'=> trans('api.required'),
            'email.exists'=> trans('api.exists'),
        ]);

        $user = User::where('email',$fields['email'])->first();
        //check email
        $token = $user->createToken('myapptoken')->plainTextToken;

        //check password
        if(!$user || !Hash::check($fields['password'], $user->password)){

            return response([ 
                'message'=>trans('api.wrong'),
            ],401);

        }

        $response = [
            'message'=>trans('api.login'),
            'user' => $user,
            'token' => $token
        ];

        return response($response,201);
    }


    public function logout(Request $request){
        auth()->user()->tokens()->delete();
        return [
            'message'=>trans('api.logout'),
        ];
    }

    public function forget(Request $request){
        $request->validate([
            'email' => 'required|string|exists:users,email',
        ]);

        $user = User::where('email',$request->email)->first();
            $response = [
                'user' => $user,
        ];

        return response($response,201);

    }

    public function loginadmin(Request $request){

        $fields = $request->validate([
            'email' => 'required|string|exists:users,email',
            'password' => 'required|string'
        ],[
            'email.required'=> trans('api.required'),
            'password.required'=> trans('api.required'),
            'email.exists'=> trans('api.exists'),
        ]);

        $user = User::where('user_type','0')->where('email',$fields['email'])->first();

        $token = $user->createToken('myapptoken')->plainTextToken;

        //check password
        if(!$user || !Hash::check($fields['password'], $user->password)){
            return response([ 
                'message'=>trans('api.wrong'),
            ],401);
        }

        $response = [
            'message'=>trans('api.login'),
            'user' => $user,
            'token' => $token
        ];

        return response($response,201);
    }

    }

