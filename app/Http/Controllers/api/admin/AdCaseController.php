<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Casee;
class AdCaseController extends Controller
{
    public function index(){
        $casees=Casee::all();
        $response = [
            'message'=>'All cases',
            'cases' => $casees
        ];
        return response($response,201);
    }

    public function show($id)
    {
        $casee=Casee::find($id);
        $response = [
            'message'=>'specific case with id',
            'case' => $casee
        ];
        return response($response,201);
    }

    public function store(Request $request){
        $request->validate([
            'name_en' => 'required|string|max:200',
            'name_ar' => 'required|string|max:200',
            'description_en' => 'string|max:500',
            'description_ar' => 'string|max:500',
            'image' => 'image|max:2048',
            'Donantiontype_id' =>'required|exists:donationtypes,id',
            'Category_id' =>'required|exists:categories,id',
            'initial_amount'=>'required|numeric',
            'status'=>'required|in:pending,accepted,published,rejected'
        ]);

        if($request->file('image')){
            $image_path = $request->file('image')->store('api/casees','public');
        }else{
            $image_path=null;
        }

        $casee = Casee::create([
            'name_en' => $request->name_en,
            'name_ar'=> $request->name_ar,
            'description_en'=> $request->description_en,
            'description_ar'=> $request->description_ar,
            'image' => asset('storage/'.$image_path),
            'Donantiontype_id'=> $request->Donantiontype_id,
            'Category_id'=> $request->Category_id,
            'initial_amount'=>$request->initial_amount,
            'status'=>$request->status,
            'User_id'=>1
        ]);

        $response = [
            'message'=>'case created successfully',
            'case' => $casee
        ];
        return response($response,201);
    }

    public function update(Request $request,$id){
        $casee=Casee::find($id);

        $request->validate([
            'name_en' => 'required|string|max:200',
            'name_ar' => 'required|string|max:200',
            'description_en' => 'string|max:500',
            'description_ar' => 'string|max:500',
            'image' => 'image|max:2048',
            'Donantiontype_id' =>'required|exists:donationtypes,id',
            'Category_id' =>'required|exists:categories,id',
            'initial_amount'=>'required|numeric',
            'status'=>'required|in:pending,accepted,published,rejected'
        ]);
        $casee->update([
            'name_en' => $request->name_en,
            'name_ar'=> $request->name_ar,
            'description_en'=> $request->description_en,
            'description_ar'=> $request->description_ar,
            'image' => asset('storage/'.$image_path),
            'Donantiontype_id'=> $request->Donantiontype_id,
            'Category_id'=> $request->Category_id,
            'initial_amount'=>$request->initial_amount,
            'status'=>$request->status,
            'User_id'=>1
                ]);
        if($request->file('image')){
            $image_path = $request->file('image')->store('api/casees','public');
            $casee->image = asset('storage/'.$image_path);
            $casee->save();
        }
        

        $response = [
            'message'=>'case updated successfully',
            'case' => $casee
        ];
        return response($response,201);
    }

    public function destroy($id){
        $casee = Casee::findOrFail($id);
        $casee->delete();
        $response = [
            'message'=>'case deleted successfully',
        ];
        return response($response,201);
    }
}
