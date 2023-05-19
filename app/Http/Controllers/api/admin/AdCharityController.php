<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AdCharityController extends Controller
{
    public function index(){
        $charities=User::where('id','!=',1)->where('user_type','2')->get();
        $response = [
            'message'=>'All charities',
            'charities' => $charities,
            'count'=>count($charities)
        ];
        return response($response,201);
    }

    public function show($id)
    {
        $charity=User::where('id',$id)->first();
        $response = [
            'message'=>'specific charity with id',
            'charity' => $charity
        ];
        return response($response,201);
    }

}
