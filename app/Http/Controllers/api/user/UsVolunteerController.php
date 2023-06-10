<?php

namespace App\Http\Controllers\api\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Volunteer;
use App\Models\User;

class UsVolunteerController extends Controller
{
    public function getUser(Request $request)
    {
        $user = User::findOrFail($request->user()->id);
        $response = [
            // 'message' => 'user who login',
            'message' => trans('api.fetch'),
            'user' => $user
        ];
        return response($response, 201);
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'city' => 'required|string',
            'address' => 'string',
            'age' => 'required|integer|min:16',
            'activity' => 'required|in:blind,feeding,medical_convoys',
            'volunteer_type' => 'required|in:individual,group',
        ], [
            'name.required' => trans('api.required'),
            'name.string' => trans('api.string'),
            'email.required' => trans('api.required'),
            'email.email' => trans('api.email'),
            'phone.required' => trans('api.required'),
            'phone.numeric' => trans('api.numeric'),
            'city.required' => trans('api.required'),
            'city.string' => trans('api.string'),
            'address.string' => trans('api.string'),
            'age.required' => trans('api.required'),
            'age.numeric' => trans('api.numeric'),
            'age.min' => trans('api.min'),
            'activity.required' => trans('api.required'),
            'volunteer_type.required' => trans('api.required'),

        ]);

        if ($request->volunteer_type == 'group') {
            $request->validate([
                'num_of_members' => 'required',
            ]);
            $num_of_member = $request->num_of_members;
        } else {
            $num_of_member = null;
        }
        $volunteer = Volunteer::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'city' => $request->city,
            'address' => $request->address,
            'age' => $request->age,
            'activity' => $request->activity,
            'volunteer_type' => $request->volunteer_type,
            'num_of_members' => $num_of_member,
            'user_id' => $request->user()->id
        ]);

        $response = [
            // 'message' => 'volunteer successfully',
            'message' => trans('api.fetch'),
            'volunteer' => $volunteer
        ];
        return response($response, 201);
    }

    public function storeGuest(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'city' => 'required|string',
            'address' => 'string',
            'age' => 'required|integer|min:16',
            'activity' => 'required|in:blind,feeding,medical_convoys',
            'volunteer_type' => 'required|in:individual,group',
        ], [
            'name.required' => trans('api.required'),
            'name.string' => trans('api.string'),
            'email.required' => trans('api.required'),
            'email.email' => trans('api.email'),
            'phone.required' => trans('api.required'),
            'phone.numeric' => trans('api.numeric'),
            'city.required' => trans('api.required'),
            'city.string' => trans('api.string'),
            'address.string' => trans('api.string'),
            'age.required' => trans('api.required'),
            'age.numeric' => trans('api.numeric'),
            'age.min' => trans('api.min'),
            'activity.required' => trans('api.required'),
            'volunteer_type.required' => trans('api.required'),

        ]);

        if ($request->volunteer_type == 'group') {
            $request->validate([
                'num_of_members' => 'required',
            ], [
                'num_of_members.required' => trans('api.required'),

            ]);
            $num_of_member = $request->num_of_members;
        } else {
            $num_of_member = null;
        }

        $volunteer = Volunteer::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'city' => $request->city,
            'address' => $request->address,
            'age' => $request->age,
            'activity' => $request->activity,
            'volunteer_type' => $request->volunteer_type,
            'num_of_members' => $num_of_member,
        ]);

        $response = [
            // 'message' => 'volunteer successfully',
            'message' => trans('api.fetch'),
            'volunteer' => $volunteer
        ];
        return response($response, 201);
    }
}
