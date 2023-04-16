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
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed',
            'user_type'=>'required|in:0,1,2',
            'phone' =>'required|numeric',
            ]);

        if($request->user_type==1){
            $fields = $request->validate([
                'gender' =>'required|in:m,f'
            ]);
            $user = User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => bcrypt($request['password']),
                'user_type'=> $request['user_type'],
                'gender'=> $request['gender'],
                'phone'=> $request['phone'],
            ]);

        }elseif($request->user_type==2){
            $fields = $request->validate([
                'address'=>'required|string|max:200',
            ]);
            $user = User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => bcrypt($request['password']),
                'user_type'=> $request['user_type'],
                'address'=> $request['address'],
                'phone'=> $request['phone'],
            ]);
        }

        
        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'message'=>'successfull register',
            'user' => $user,
            'token' => $token
        ];

        return response($response,201);
    }

    public function login(Request $request){
        $fields = $request->validate([


            'email' => 'required|string|exists:users,email',
            'password' => 'required|string|'

        ]);

        $user = User::where('email',$fields['email'])->first();
        //check email
        $token = $user->createToken('myapptoken')->plainTextToken;

        //check password
        if(!$user || !Hash::check($fields['password'], $user->password)){

            return response([ 
                'message' => 'wrong'
            ],401);

        }

        $response = [
            'message'=>'successfull login',
            'user' => $user,
            'token' => $token
        ];

        return response($response,201);
    }


    public function logout(Request $request){
        auth()->user()->tokens()->delete();
        return [
            'messege' =>'Logged out'
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
        ]);

        $user = User::where('user_type','0')->where('email',$fields['email'])->first();

        $token = $user->createToken('myapptoken')->plainTextToken;

        //check password
        if(!$user || !Hash::check($fields['password'], $user->password)){

            return response([ 'message' => 'wrong'],401);

        }

        $response = [
            'message'=>'successfull login',
            'user' => $user,
            'token' => $token
        ];

        return response($response,201);
    }

    }

