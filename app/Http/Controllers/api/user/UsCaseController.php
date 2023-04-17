<?php

namespace App\Http\Controllers\api\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Casee;

class UsCaseController extends Controller
{
    public function index(){

        $casees=Casee::select(
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
            )->with('category','donationtype','user')->where('status','published')->get();

        $response = [
            'message'=>'All cases',
            'cases' => $casees
        ];
        return response($response,201);
    }

    public function show($id)
    {

        $casee=Casee::select(
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
            )->with('category','donationtype','user')->where('id',$id)->first();
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
            'donationtype_id' =>'required|exists:donationtypes,id',
            'category_id' =>'required|exists:categories,id',
            'initial_amount'=>'required|numeric',
        ]);

        if($request->file('image')){
            $image_path = $request->file('image')->store('api/casees','public');
            $image=asset('storage/'.$image_path);
        }else{
            $image=null;
        }

        $casee = Casee::create([
            'name_en' => $request->name_en,
            'name_ar'=> $request->name_ar,
            'description_en'=> $request->description_en,
            'description_ar'=> $request->description_ar,
            'image' => $image,
            'donationtype_id'=> $request->donationtype_id,
            'category_id'=> $request->category_id,
            'initial_amount'=>$request->initial_amount,
            'paied_amount'=>0,
            'remaining_amount'=>0,
            'user_id'=>$request->user()->id,
            'status'=>'pending'
        ]);

        $response = [
            'message'=>'case created successfully',
            'case' => $casee
        ];
        return response($response,201);
    }

    public function update(Request $request,$id){
        $casee=Casee::find($id);
        if($casee->user_id==$request->user()->id){
        if($casee->status=='pending'){
            $request->validate([
                'name_en' => 'required|string|max:200',
                'name_ar' => 'required|string|max:200',
                'description_en' => 'string|max:500',
                'description_ar' => 'string|max:500',
                'image' => 'image|max:2048',
                'donationtype_id' =>'required|exists:donationtypes,id',
                'category_id' =>'required|exists:categories,id',
                'initial_amount'=>'required|numeric',
                'status'=>'required|in:pending'
            ]);
        }else{

        $request->validate([
            'name_en' => 'required|string|max:200',
            'name_ar' => 'required|string|max:200',
            'description_en' => 'string|max:500',
            'description_ar' => 'string|max:500',
            'image' => 'image|max:2048',
            'donationtype_id' =>'required|exists:donationtypes,id',
            'category_id' =>'required|exists:categories,id',
            'initial_amount'=>'required|numeric',
            'status'=>'required|in:accepted,published'
        ]);}

        if($request->file('image')){
            $image_path = $request->file('image')->store('api/casees','public');
            $image=asset('storage/'.$image_path);
        }else{
            $image=$casee->image;
        }



        $casee->update([
            'name_en' => $request->name_en,
            'name_ar'=> $request->name_ar,
            'description_en'=> $request->description_en,
            'description_ar'=> $request->description_ar,
            'image' => $image,
            'donationtype_id'=> $request->donationtype_id,
            'category_id'=> $request->category_id,
            'initial_amount'=>$request->initial_amount,
            'user_id'=>$request->user()->id,
            'status'=>$request->status
                ]);
        
        
        $response = [
            'message'=>'case updated successfully',
            'case' => $casee
        ];}
        else{
            $response = [
                'message'=>'can not be updated Unauthorized'];
        }
        return response($response,201);
    }

    public function destroy(Request $request,$id){

        $casee = Casee::findOrFail($id);
        if($casee->user_id==$request->user()->id){
            $casee->delete();
        $response = [
            'message'=>'case deleted successfully',
        ];
        
        }else{
            $response = [
                'message'=>'can not be deleted ',
            ];
        }
            return response($response,201);
    }

    public function casesOfCategory($categoryid){
        $casees=Casee::select(
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
            )->with('category','donationtype','user')->where('status','published')->where('category_id',$categoryid)->get();

        $response = [
            'message'=>'All cases of category',
            'cases' => $casees
        ];
        return response($response,201);
    }
}
