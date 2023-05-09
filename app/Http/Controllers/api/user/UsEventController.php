<?php

namespace App\Http\Controllers\api\user;

use App\Models\User;
use App\Models\Event;
use App\Models\Volunteer;
use Illuminate\Http\Request;
use App\Models\EventVolunteer;
use App\Models\EvenntVolunteer;
use App\Http\Controllers\Controller;

class UsEventController extends Controller
{
    public function index()
    {
        $events = Event::select(
            'id',
            'name_'.app()->getLocale().' as name',
            'description_'.app()->getLocale().' as description',
            'image',
            'start_date',
            'end_date',
            'start_time',
            'end_time'
            )->where('user_id',1)->get();

        $response = [
        'message' => 'All Events',
        'result' => $events
        ];
        return response($response, 201);
    }
    public function show(string $id)
    {
        $event = Event::select(
            'id',
            'name_'.app()->getLocale().' as name',
            'description_'.app()->getLocale().' as description',
            'image',
            'start_date',
            'end_date',
            'start_time',
            'end_time'
        )->where('id',$id)->first();        
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
    public function showLatestEvents()
    {
        $events = Event::select(
            'id',
            'name_'.app()->getLocale().' as name',
            'description_'.app()->getLocale().' as description',
            'image',
            'start_date',
            'end_date',
            'start_time',
            'end_time'
        )->latest()->take(3)->get();
        $response = [
            'message' => 'Latest events.',
            'events' => $events
        ];
        return response($response, 201);
    }

    public function joinToEvent(Request $request, $id)
    {

        if ($request->user()->user_type == 1) {
            $event = Event::find($id);
            if ($event) {
                $check_join = EvenntVolunteer::where('event_id', $id)->where('volunteer_id', $request->user()->id)->first();
                if ($check_join == false) {
                    $eventvolunteer = EvenntVolunteer::create(
                        [
                            'event_id' => $event->id,
                            'volunteer_id' => $request->user()->id,
                            'joined' => true,
                        ]
                    );
                    $response = [
                        'message' => 'Volunteer Joined Successfully.',
                        'EventVolunteer' => $eventvolunteer,
                    ];
                } else {
                    $response = [
                        'message' => 'You are already joined.',
                    ];
                }
            } else {
                $response = [
                    'message' => 'Event is not exist.',
                ];
            }
        } else {
            $response = [
                'message' => "Charity can't join to event.",
            ];
        }
        return response($response, 201);
    }
}
