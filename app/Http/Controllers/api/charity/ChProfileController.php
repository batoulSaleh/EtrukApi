<?php

namespace App\Http\Controllers\api\charity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;


class ChProfileController extends Controller
{
    public function show(Request $request)
    {
        $charity=User::where('id',$request->user()->id)->first();
        $response = [
            'message'=>' charity which logined',
            'charity' => $charity
        ];
        return response($response,201);
    }

    public function update(Request $request){
        $charity=User::findOrFail($request->user()->id);
        $request->validate([
            'name_en' => 'required|string',
            'name_ar' => 'required|string',
            'description_en' => 'string|max:500',
            'description_ar' => 'string|max:500',
            'address'=>'required|string|max:200',
            'image' => 'required|image|max:2048',
            'phone' =>'required|numeric',
        ]);

        $charity->update([
            'name_en' => $request->name_en,
            'name_ar' => $request->name_ar,
            'description_en'=> $request->description_en,
            'description_ar'=> $request->description_ar,
            'phone' =>$request->phone,
            'address' =>$request->address,
        ]);

        if ($request->file('image')) {
            $image_path = $request->file('image')->store('api/users', 'public');
            $charity->image = asset('storage/' . $image_path);
            $charity->save();
        }

        $response = [
            'message'=>'edited successfully',
            'charity' => $charity
        ];

        return response($response,201);
    }
}
