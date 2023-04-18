<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Volunteer;


class AdVolunteerController extends Controller
{
    public function index(){
        $volunteers=Volunteer::all();
        $response = [
            'message'=>'All volunteers',
            'volunteers' => $volunteers
        ];
        return response($response,201);
    }

    public function show($id)
    {
        $volunteer=Volunteer::findOrFail($id);
        $response = [
            'message'=>'specific volunteer with id',
            'volunteer' => $volunteer
        ];
        return response($response,201);
    }

}