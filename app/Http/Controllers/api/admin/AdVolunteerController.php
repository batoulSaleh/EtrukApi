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
            'volunteers' => $volunteers,
            'count'=>count($volunteers)
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

    public function destroy($id){
        $volunteer=Volunteer::findOrFail($id);
        $volunteer->delete();
        $response = [
            'message'=>'volunteer deleted successfully',
        ];
        return response($response,201);
    }

}
