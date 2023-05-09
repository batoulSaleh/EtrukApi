<?php

namespace App\Http\Controllers\api\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Casee;
use App\Models\Event;
use App\Models\Category;


class UsCharityController extends Controller
{
    public function index(){
        $charities =User::where('id','!=',1)->where('user_type','2')->get();
        $response = [
            'message'=>'All charities',
            'charities' => $charities
        ];
        return response($response,201);
    }

    public function show($id){
        $charity =User::findOrFail($id);
        $response = [
            'message'=>'specific charity',
            'charity' => $charity
        ];
        return response($response,201);
    }

    public function getCases($id){
        $casees=Casee::with('item')->select(
            'id',
            'name_'.app()->getLocale().' as name',
            'description_'.app()->getLocale().' as description',
            'image',
            'initial_amount',
            'paied_amount',
            'remaining_amount',
            'status',
            'user_id',
            'donationtype_id',
            'category_id'
            )->with('category','donationtype','user')->where('user_id',$id)->where('status','published')->get();

        $response = [
            'message'=>'All cases',
            'cases' => $casees
        ];
        return response($response,201);
    }

    public function getEvents($id){
        $events = Event::select(
            'id',
            'name_'.app()->getLocale().' as name',
            'description_'.app()->getLocale().' as description',
            'image',
            'start_date',
            'end_date',
            'start_time',
            'end_time'
            )->where('user_id',$id)->get();

        $response = [
        'message' => 'All Events',
        'result' => $events
        ];
        return response($response, 201);
    }


}
