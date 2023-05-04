<?php

namespace App\Http\Controllers\api\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

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
}
