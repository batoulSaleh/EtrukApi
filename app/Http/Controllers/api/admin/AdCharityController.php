<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Casee;
use App\Models\Event;
use App\Models\User;
use Carbon\CarbonPeriod;
use Carbon\Carbon;

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


    public function showUpdate(Request $request)
    {
        $charity=User::where('id',$request->user()->id)->first();
        $response = [
            'message'=>' charity which logined',
            'charity' => $charity
        ];
        return response($response,201);
    }

    public function getcases(Request $request){
        $casees=Casee::with('item','caseimage')->where('user_id',$request->user()->id)->get();
        $response = [
            'message'=>'All cases',
            'cases' => $casees,
            'count'=>count($casees)
        ];
        return response($response,201);
    }


    public function getEvents(Request $request){
        $events = Event::where('user_id',$request->user()->id)->get();
        $response = [
            'message' => 'All Events',
            'result' => $events,
            'count' =>count($events)
        ];
        return response($response, 201);
    }


    public function storeEvent(Request $request)
    {
        $request->validate([
            'name_en' => 'required|string|max:200',
            'name_ar' => 'required|string|max:200',
            'description_en' => 'string|max:500',
            'description_ar' => 'string|max:500',
            'image' => 'image|max:2048',
            'start_date' => 'required',
            'end_date' => 'required',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
        ]);
        if ($request->file('image')) {
            $image_path = $request->file('image')->store('api/events', 'public');
        } else {
            $image_path = null;
        }
        $event = Event::create(
            [
                'name_en' => $request->name_en,
                'name_ar' => $request->name_ar,
                'image' => asset('storage/' . $image_path),
                'description_en' => $request->description_en,
                'description_ar' => $request->description_ar,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'created_at' => Carbon::now(),
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'user_id'=>$request->user()->id
            ]
        );
        $response = ['message' => 'Event is created successfully.', 'result' => $event];
        return response($response, 201);
    }

    public function updateEvent(Request $request, string $id)
    {
        $event = Event::find($id);
        if($event->user_id==$request->user()->id){
        $request->validate([
            'name_en' => 'required|string|max:200',
            'name_ar' => 'required|string|max:200',
            'description_en' => 'string|max:500',
            'description_ar' => 'string|max:500',
            'image' => 'image|max:2048',
            'start_date' => 'required',
            'end_date' => 'required',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
        ]);
        $event->update(
            [
                'name_en' => $request->name_en,
                'name_ar' => $request->name_ar,
                'description_en' => $request->description_en,
                'description_ar' => $request->description_ar,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'updated' => Carbon::now(),
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
            ]
        );
        if ($request->file('image')) {
            $image_path = $request->file('image')->store('api/events', 'public');
            $event->image = asset('storage/' . $image_path);
            $event->save();
        }
        $response = ['message' => 'Event is updated successfully.', 'result' => $event];}
        else{
            $response = [
                'message'=>'can not be updated Unauthorized'];
        }
        return response($response, 201);
    }

    public function edit(Request $request){
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

    public function destroyEvent(string $id)
    {
        $event = Event::findOrFail($id);
        if($event->user_id==$request->user()->id){
        $event->delete();
        $response = ['message' => 'Event is deleted successfully.'];}
        else{
            $response = [
                'message'=>'can not be updated Unauthorized'];
        }
        return response($response, 201);
    }

    public function destroyCase($id){
        $casee = Casee::findOrFail($id);
        if($casee->user_id==$request->user()->id){
        $casee->delete();
        $response = [
            'message'=>'case deleted successfully',
        ];}
        else{
            $response = [
                'message'=>'can not be updated Unauthorized'];
        }
        return response($response,201);
    }

}
