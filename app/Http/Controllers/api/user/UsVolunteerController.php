<?php

namespace App\Http\Controllers\api\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Volunteer;

class UsVolunteerController extends Controller
{
    public function storeUser(Request $request){
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'string',
            'city' => 'required|string',
            'address' => 'string',
            'age' => 'required|integer|min:16',
            'activity' => 'required|in:blind,feeding,medical_convoys',
            'volunteer_type' => 'required|in:individual,group',
        ]);

        if($request->volunteer_type=='group'){
            $request->validate([
                'num_of_members' => 'required',
                ]);
            $num_of_member=$request->num_of_members;
            }
            $num_of_member=null;

        $volunteer=Volunteer::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'city' => $request->city,
            'address' => $request->address,
            'age' => $request->age,
            'activity' => $request->activity,
            'volunteer_type' => $request->volunteer_type,
            'num_of_member'=>$num_of_member,
            'user_id'=>$request->user()->id
        ]);

        $response = [
            'message'=>'volunteer successfully',
            'volunteer' => $volunteer
        ];
        return response($response,201);
    }

    public function storeGuest(Request $request){
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'string',
            'city' => 'required|string',
            'address' => 'string',
            'age' => 'required|integer|min:16',
            'activity' => 'required|in:blind,feeding,medical_convoys',
            'volunteer_type' => 'required|in:individual,group',
        ]);

        if($request->volunteer_type=='group'){
            $request->validate([
                'num_of_members' => 'required',
                ]);
            $num_of_member=$request->num_of_members;
            }
            $num_of_member=null;

        $volunteer=Volunteer::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'city' => $request->city,
            'address' => $request->address,
            'age' => $request->age,
            'activity' => $request->activity,
            'volunteer_type' => $request->volunteer_type,
            'num_of_member'=>$num_of_member,
        ]);

        $response = [
            'message'=>'volunteer successfully',
            'volunteer' => $volunteer
        ];
        return response($response,201);
    }
}
