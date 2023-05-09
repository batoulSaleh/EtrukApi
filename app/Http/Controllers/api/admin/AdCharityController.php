<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Casee;
use App\Models\Event;
use Carbon\CarbonPeriod;
use Carbon\Carbon;

class AdCharityController extends Controller
{
    public function getcases(Request $request){
        $casees=Casee::with('item')->where('user_id',$request->user()->id)->get();
        $response = [
            'message'=>'All cases',
            'cases' => $casees
        ];
        return response($response,201);
    }


    public function getEvents(Request $request){
        $events = Event::where('user_id',$request->user()->id)->get();
        $response = [
            'message' => 'All Events',
            'result' => $events
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
            $image_path = $request->file('image')->store('api/events', 'public'); //store('name of folder', 'in folder public');
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

}
