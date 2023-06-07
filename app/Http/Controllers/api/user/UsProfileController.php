<?php

namespace App\Http\Controllers\api\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Casee;
use App\Models\Donation;

class UsProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = User::findOrFail($request->user()->id);
        $response = [
            // 'message'=>'user who login',
            'message' => trans('api.fetch'),
            'user' => $user
        ];
        return response($response, 201);
    }

    public function edit(Request $request)
    {
        $user = User::findOrFail($request->user()->id);
        $request->validate([
            'name_en' => 'required|string',
            'phone' => 'required|numeric',
            'gender' => 'required|in:m,f',
            'image' => 'image|max:2048',
        ], [
            'name_en.required' => trans('api.required'),
            'phone.required' => trans('api.required'),
            'gender.required' => trans('api.required'),
        ]);

        $user->update([
            'name_en' => $request->name_en,
            'phone' => $request->phone,
            'gender' => $request->gender,
        ]);

        if ($request->file('image')) {
            $image_path = $request->file('image')->store('api/users', 'public');
            $user->image = asset('storage/' . $image_path);
            $user->save();
        }

        $response = [
            // 'message'=>'edited successfully',
            'message' => trans('api.updated'),
            'user' => $user
        ];

        return response($response, 201);
    }


    public function casesOfUser(Request $request)
    {
        $cases = Casee::where('user_id', $request->user()->id)->with('category','donationtype','user','item','caseimage')->get();
        $response = [
            // 'message'=>'all cases of user',
            'message' => trans('api.fetch'),
            'cases' => $cases,
            'count' => count($cases)
        ];

        return response($response, 201);
    }

    public function donationsOfUser(Request $request)
    {
        $donations = Donation::where('user_id', $request->user()->id)->where('status', 'accepted')->get();
        $response = [
            // 'message'=>'all donations of user',
            'message' => trans('api.fetch'),
            'donations' => $donations,
            'count' => count($donations)
        ];
        return response($response, 201);
    }
}
