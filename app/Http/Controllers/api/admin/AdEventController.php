<?php

namespace App\Http\Controllers\api\admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Event;
use Carbon\CarbonPeriod;
use App\Models\Volunteer;
use App\models\availability;
use Illuminate\Http\Request;
use App\Models\EvenntVolunteer;
use App\Http\Controllers\Controller;

class AdEventController extends Controller
{
    public function index()
    {
        $events = Event::all();
        $response = ['message' => 'All Events', 'result' => $events];
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
            'start_time_desc' => 'required|in:am,pm',
            'end_time_desc' => 'required|in:am,pm',
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
                'start_time_desc' =>  $request->start_time_desc,
                'end_time_desc' =>  $request->end_time_desc,
            ]
        );
        $response = ['message' => 'Event is created successfully.', 'result' => $event];
        return response($response, 201);
    }



    public function show(string $id)
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

    public function update(Request $request, string $id)
    {
        $event = Event::find($id);
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
            'start_time_desc' => 'required|in:am,pm',
            'end_time_desc' => 'required|in:am,pm',
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
                'start_time_desc' =>  $request->start_time_desc,
                'end_time_desc' =>  $request->end_time_desc,
            ]
        );
        if ($request->file('image')) {
            $image_path = $request->file('image')->store('api/events', 'public');
            $event->image = asset('storage/' . $image_path);
            $event->save();
        }
        $response = ['message' => 'Event is updated successfully.', 'result' => $event];
        return response($response, 201);
    }


    public function destroy(string $id)
    {
        $event = Event::findOrFail($id);
        $event->delete();
        $response = ['message' => 'Event is deleted successfully.'];
        return response($response, 201);
    }
}

























 // public function InsertDate()
    // {
    //     $begin = Carbon::parse('2023-01-27');
    //     $end = Carbon::parse('2023-03-25');
    //     $dates = CarbonPeriod::create($begin, $end)->toArray();
    //     $now = now(); // needed for created_at and updated_at
    //     $attributes = [];
    //     foreach ($dates as $date) {
    //         $attributes[] = [
    //             'start_date_time' => $date->hour(10)->minute(0)->seconds(0),
    //             'end_date_time' => $date->hour(12)->minute(0)->seconds(0),
    //             'user_id' => $user->id,
    //             // must add these because we are using insert()
    //             'created_at' => $now,
    //             'updated_at' => $now,
    //         ];
    //     }

    //     availability::insert($attributes);
    // }
