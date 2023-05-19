<?php

namespace App\Http\Controllers\api\charity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Donationtype;

class ChDonationTypeController extends Controller
{
    public function index(){
        $donationtypes=Donationtype::all();
        $response = [
            'message'=>'All Donationtypes',
            'Donationtypes' => $donationtypes
        ];
        return response($response,201);
    }

    public function show($id)
    {
        $donationtype=Donationtype::find($id);
        $response = [
            'message'=>'specific Donationtype with id',
            'Donationtype' => $donationtype
        ];
        return response($response,201);
    }

}
