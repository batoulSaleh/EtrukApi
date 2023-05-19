<?php

namespace App\Http\Controllers\api\charity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Event;
use Carbon\CarbonPeriod;
use App\Models\Volunteer;
use App\models\availability;
use App\Models\EvenntVolunteer;

class ChEventController extends Controller
{
    public function index(Request $request)
    {
        $events = Event::where('user_id',$request->user()->id)->get();
        $response = [
            'message' => 'All Events',
            'result' => $events,
            'count' =>count($events)
        ];
        return response($response, 201);
    }

    public function show($id)
    {
        $event = Event::find($id);
        $ids = EvenntVolunteer::where('event_id', $id)->get();
        $id_volunteers = $ids->pluck('volunteer_id')->toArray();
        $volunteers = User::whereIn('id', $id_volunteers)->get();
        $response = [
            'message' => 'A specific event with id.',
            'event' => $event,
            'volunteers' => $volunteers,
            'count_volunteers' => count($volunteers),
        ];
        return response($response, 201);
    }

    public function store(Request $request)
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

    public function update(Request $request,$id)
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

    public function destroy(Request $request,$id)
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
}
