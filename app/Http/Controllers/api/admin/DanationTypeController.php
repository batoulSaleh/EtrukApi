<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Donationtype;

class DonationTypeController extends Controller
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

    public function store(Request $request){
        $request->validate([
            'name_en' => 'required|string|max:200',
            'name_ar' => 'required|string|max:200',
        ]);


        $donationtype = Donationtype::create([
            'name_en' => $request->name_en,
            'name_ar'=> $request->name_ar,
        ]);

        $response = [
            'message'=>'Donationtype created successfully',
            'Donationtype' => $donationtype
        ];
        return response($response,201);
    }

    public function update(Request $request,$id){
        $donationtype=Donationtype::find($id);

        $request->validate([
            'name_en' => 'required|string|max:200',
            'name_ar' => 'required|string|max:200',
        ]);

        $donationtype->update([
                    'name_en' => $request->name_en,
                    'name_ar'=> $request->name_ar,
                ]);
        

        $response = [
            'message'=>'Donationtype updated successfully',
            'Donationtype' => $donationtype
        ];
        return response($response,201);
    }

    public function destroy($id){
        $donationtype = Donationtype::findOrFail($id);
        $donationtype->delete();
        $response = [
            'message'=>'Donationtype deleted successfully',
        ];
        return response($response,201);
    }
}
