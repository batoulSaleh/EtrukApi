<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Casee;
use App\Models\Item;

class AdCaseController extends Controller
{
    public function index(){
        $casees=Casee::with('item')->get();
        $response = [
            'message'=>'All cases',
            'cases' => $casees
        ];
        return response($response,201);
    }

    public function show($id)
    {
        $casee=Casee::where('id',$id)->with('category','donationtype','user')->get();
        if($casee->donationtype_id==5){
            $items=Item::where('casee_id',$casee->id)->get();   
                $response = [
                    'message'=>'specific case with id',
                    'case' => $casee,
                    'items'=>$items,
                ];
        }
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
            'status'=>'required|in:pending,accepted,published,rejected'
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
            'remaining_amount'=>$request->initial_amount,
            'status'=>$request->status,
            'user_id'=>1
        ]);

        $response = [
            'message'=>'case created successfully',
            'case' => $casee
        ];
        return response($response,201);
    }

    public function storeFurniture(Request $request){
        $request->validate([
            'name_en' => 'required|string|max:200',
            'name_ar' => 'required|string|max:200',
            'description_en' => 'string|max:500',
            'description_ar' => 'string|max:500',
            'image' => 'image|max:2048',
            'donationtype_id' =>'required|in:5',
            'category_id' =>'required|exists:categories,id',
            'status'=>'required|in:pending,accepted,published,rejected',
            'items'=>'required'
        ]);

        if($request->file('image')){
            $image_path = $request->file('image')->store('api/casees','public');
            $image=asset('storage/'.$image_path);
        }else{
            $image=null;
        }

        $initial_amount=0;
        $items=$request->items;
        

        $casee = Casee::create([
            'name_en' => $request->name_en,
            'name_ar'=> $request->name_ar,
            'description_en'=> $request->description_en,
            'description_ar'=> $request->description_ar,
            'image' => $image,
            'donationtype_id'=> $request->donationtype_id,
            'category_id'=> $request->category_id,
            'initial_amount'=>$initial_amount,
            'paied_amount'=>0,
            'remaining_amount'=>$request->initial_amount,
            'status'=>$request->status,
            'user_id'=>1
        ]);

        foreach($items as $item){
            Item::create([
                'name_ar'=>$item['name_ar'],
                'name_en'=>$item['name_en'],
                'amount'=>$item['amount'],
                'casee_id'=>$casee->id,

            ]);

            $initial_amount=$initial_amount +$item['amount'];

        }

        $casee->update([
            'initial_amount'=>$initial_amount,
        ]);

        $final_items=Item::select(
            'id',
            'name_'.app()->getLocale().' as name',
            'amount',
            'casee_id'
            )->where('casee_id',$casee->id)->get();

        $response = [
            'message'=>'case created successfully',
            'case' => $casee,
            'items'=>$final_items,
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
            'donationtype_id' =>'required|exists:donationtypes,id',
            'category_id' =>'required|exists:categories,id',
            'initial_amount'=>'required|numeric',
            'status'=>'required|in:pending,accepted,published,rejected'
        ]);

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
            'status'=>$request->status,
                ]);
        
        

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
