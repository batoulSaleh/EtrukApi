<?php

namespace App\Http\Controllers\api\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Casee;
use App\Models\Donation;

class UsProfileController extends Controller
{
    public function show(Request $request){
        $user=User::findOrFail($request->user()->id);
        $response = [
            'message'=>'user who login',
            'user' => $user
        ];
        return response($response,201);
    }

    public function edit(Request $request){
        $user=User::findOrFail($request->user()->id);
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'phone' =>'required|numeric',
            'gender' =>'required|in:m,f',
            'image' => 'image|max:2048',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' =>$request->phone,
            'gender' =>$request->gender,
            'image' => $request->image,
        ]);

        $response = [
            'message'=>'edited successfully',
            'user' => $user
        ];

        return response($response,201);
    }


    public function casesOfUser(Request $request){
        $cases=Casee::where('user_id',$request->user()->id)->get();
        $response = [
            'message'=>'all cases of user',
            'cases' => $cases
        ];

        return response($response,201);

    }

    public function donationsOfUser(Request $request){
        $donations=Donation::where('user_id',$request->user()->id)->where('status','accepted')->get();
        $response = [
            'message'=>'all donations of user',
            'donations' => $donations
        ];

        return response($response,201);
    }


}
