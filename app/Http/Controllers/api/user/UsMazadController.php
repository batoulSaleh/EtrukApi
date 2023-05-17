<?php

namespace App\Http\Controllers\api\user;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Mazad;
use App\Models\Volunteer;
use App\Models\Mazadimage;
use App\Models\MazadVendors;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UsMazadController extends Controller
{
    /** Show all auctions **/
    public function index()
    {
        $auctions = Mazad::with('mazadimage')->select(
            'id',
            'name_' . app()->getLocale() . ' as name',
            'description_' . app()->getLocale() . ' as description',
            'end_date',
            'end_time',
            'created_at',
            'current_price',
        )->where('status', 'accepted')->orWhere('status', 'finished')->get();
        $response = [
            'message' => 'All auctions',
            'auctions' => $auctions,
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
            'end_date' => 'required',
            'end_time' => 'required|date_format:H:i:s',
            'starting_price' => 'required|numeric',
            'mazad_amount' => 'required|numeric',
        ]);
        $auction = Mazad::create(
            [
                'name_en' => $request->name_en,
                'name_ar' => $request->name_ar,
                'description_en' => $request->description_en,
                'description_ar' => $request->description_ar,
                'created_at' => Carbon::now(),
                'starting_price' => $request->starting_price,
                'mazad_amount' => $request->mazad_amount,
                'current_price' => $request->starting_price,
                'end_date' => $request->end_date,
                'end_time' => $request->end_time,
                'status' => 'pending',
                'owner_id' => $request->user()->id,
            ]
        );
        $images = $request->file('images');
        if ($images) {
            foreach ($images as $image) {
                $image_path = $image->store('api/mazads', 'public');
                $image = asset('storage/' . $image_path);

                Mazadimage::create([
                    'mazad_id' => $auction->id,
                    'image' => $image
                ]);
            }
        }
        $response = ['message' => 'Auction is created successfully.', 'result' => $auction];
        return response($response, 201);
    }
    public function show($id)
    {
        $mazad = Mazad::with('mazadimage')->where('id', $id)->first();
        $owner = User::find($mazad->owner_id);
        $response = [
            'message' => 'A specific mazad with id of owner.',
            'mazad' => $mazad,
            'the_owner_name' => $owner->name_en,
            'the_owner_email' => $owner->email,
        ];
        return response($response, 201);
    }

    public function mazadIncrement(Request $request,  $id)
    {
        $mazad = Mazad::with('mazadimage')->where('id', $id)->first();
        $vendor_id = $request->user()->id;
        $vendor = User::findorfail($vendor_id);
        if ($request->user()->id != $mazad->owner_id) //
        {
            $currentBid = $mazad->current_price;
            $newBid = $request->vendor_paid;
            if ($newBid > $currentBid) {
                $mazad->update(
                    [
                        'current_price' => $newBid,
                    ]
                );
                $auction = MazadVendors::create(
                    [
                        'vendor_id' => $request->user()->id,
                        'mazad_id' => $mazad->id,
                        'vendor_paid' => $request->vendor_paid,
                        'vendor_paid_time' => Carbon::now(),
                    ]
                );
                $response = [
                    'message' => 'Increment Successfully.',
                    'result' => $auction,
                    'mazad' => $mazad,
                    'vendor' => $vendor,
                ];
                return response($response, 201);
            } else {
                $response = ['message' => 'Should New Bid to be greater than Current Bid.',];
                return response($response, 201);
            }
        } else {
            $response = ['message' => "You can't participate in the auction.",];
            return response($response, 201);
        }
    }
    public function historyOfMazad($id)
    {
        // $mazad = Mazad::find($id);
        $mazad = Mazad::with('mazadimage')->where('id', $id)->first();
        $history_of_mazad = MazadVendors::where('mazad_id', $id)->get();
        $ids = MazadVendors::where('mazad_id', $id)->get();
        $id_volunteers = $ids->pluck('vendor_id')->toArray();
        $users = User::whereIn('id', $id_volunteers)->get();
        $response = [
            'history' => $history_of_mazad,
        ];
        return response($response, 201);
    }
    public function latestshow()
    {
        $auctions = Mazad::with('mazadimage')->select(
            'id',
            'name_' . app()->getLocale() . ' as name',
            'description_' . app()->getLocale() . ' as description',
            'end_date',
            'end_time',
            'created_at',
            'current_price',
        )->where('status', 'accepted')->latest()->take(3)->get();

        $response = [
            'message' => 'The latest auctions.',
            'mazad' => $auctions,
        ];
        return response($response, 201);
    }
    public function auctionsOfUser($id)
    {
        // $mazad = Mazad::find($id);
        $mazad = Mazad::with('mazadimage')->where('id', $id)->first();
        $owner = User::find($mazad->owner_id);
        // $other_auctions = Mazad::all()->where('owner_id', $mazad->owner_id);
        $other_auctions = Mazad::with('mazadimage')->select(
            'id',
            'name_' . app()->getLocale() . ' as name',
            'description_' . app()->getLocale() . ' as description',
            'end_date',
            'end_time',
            'created_at',
            'current_price',
            'owner_id',
        )->where('owner_id', $mazad->owner_id)->get();
        $response = [
            'message' => 'Other mazads of the owner of mazad.',
            'the_owner_name' => $owner->name_en,
            'others' => $other_auctions,
        ];
        return response($response, 201);
    }
}
