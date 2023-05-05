<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Casee;
use App\Models\Item;
use App\Models\Donationitem;

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
        $casee=Casee::where('id',$id)->with('category','donationtype','user','item')->first();
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
            'status'=>'required|in:pending,accepted,published,rejected'
        ]);
        if($request->file('image')){
            $image_path = $request->file('image')->store('api/casees','public');
            $image=asset('storage/'.$image_path);
        }else{
            $image=null;
        }

        if($request->donationtype_id==5){
            $request->validate([
                'items'=>'required'
            ]);
    
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
                'remaining_amount'=>$initial_amount,
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
                'remaining_amount'=>$initial_amount,
            ]);
    
            $final_items=Item::where('casee_id',$casee->id)->get();
    
            $response = [
                'message'=>'case created successfully',
                'case' => $casee,
                'items'=>$final_items,
            ];
        }elseif($request->donationtype_id==4){
            $request->validate([
                'description_en' => 'required|string|max:500',
                'description_ar' => 'required|string|max:500',
                'type_en' => 'required|string|max:500',
                'type_ar' => 'required|string|max:500',
                'initial_amount'=>'required|numeric',
            ]);

            $casee = Casee::create([
                'name_en' => $request->name_en,
                'name_ar'=> $request->name_ar,
                'description_en'=> $request->description_en,
                'description_ar'=> $request->description_ar,
                'type_en' => $request->type_en,
                'type_ar' => $request->type_ar,
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

        }else{

            $request->validate([
                'initial_amount'=>'required|numeric',
            ]);

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
        }

        return response($response,201);
    }


    public function update(Request $request,$id){
        $casee=Casee::find($id);

        $request->validate([
            'donationtype_id' =>'required|exists:donationtypes,id',
        ]);

        $items=Item::where('casee_id',$casee->id)->get();
        if($items){
            foreach($items as $item){
            $item->delete();
            }
        }
        
        if($casee->donationtype_id==4){
            $casee->update([
                'type_en' => null,
                'type_ar' => null,
            ]);
        }

        if($request->donationtype_id==5){
            $request->validate([
                'name_en' => 'required|string|max:200',
                'name_ar' => 'required|string|max:200',
                'description_en' => 'string|max:500',
                'description_ar' => 'string|max:500',
                'image' => 'image|max:2048',
                'category_id' =>'required|exists:categories,id',
                'status'=>'required|in:pending,accepted,published,rejected',
                'items'=>'required'
            ]);

            if($request->file('image')){
                $image_path = $request->file('image')->store('api/casees','public');
                $image=asset('storage/'.$image_path);
            }else{
                $image=$casee->image;
            }

            $initial_amount=0;
            $items=$request->items;

            $casee->update([
                    'name_en' => $request->name_en,
                    'name_ar'=> $request->name_ar,
                    'description_en'=> $request->description_en,
                    'description_ar'=> $request->description_ar,
                    'image' => $image,
                    'donationtype_id'=> $request->donationtype_id,
                    'category_id'=> $request->category_id,
                    'initial_amount'=>$initial_amount,
                    'remaining_amount'=>$initial_amount,
                    'status'=>$request->status,
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
                'remaining_amount'=>$initial_amount,
            ]);
    
            $final_items=Item::where('casee_id',$casee->id)->get();
    
            $response = [
                'message'=>'case updated successfully',
                'case' => $casee,
                'items'=>$final_items,
            ];
        }elseif($request->donationtype_id==4){
            $request->validate([
                'name_en' => 'required|string|max:200',
                'name_ar' => 'required|string|max:200',
                'description_en' => 'required|string|max:500',
                'description_ar' => 'required|string|max:500',
                'type_en' => 'required|string|max:500',
                'type_ar' => 'required|string|max:500',
                'image' => 'image|max:2048',
                'category_id' =>'required|exists:categories,id',
                'status'=>'required|in:pending,accepted,published,rejected',
                'initial_amount'=>'required|numeric',
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
                'type_en' => $request->type_en,
                'type_ar' => $request->type_ar,
                'image' => $image,
                'donationtype_id'=> $request->donationtype_id,
                'category_id'=> $request->category_id,
                'initial_amount'=>$request->initial_amount,
                'remaining_amount'=>$request->initial_amount,
                'status'=>$request->status,
            ]);

            $response = [
                'message'=>'case updated successfully',
                'case' => $casee
            ];
        }
        else{
            $request->validate([
                'name_en' => 'required|string|max:200',
                'name_ar' => 'required|string|max:200',
                'description_en' => 'string|max:500',
                'description_ar' => 'string|max:500',
                'image' => 'image|max:2048',
                'category_id' =>'required|exists:categories,id',
                'status'=>'required|in:pending,accepted,published,rejected',
                'initial_amount'=>'required|numeric',
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
                'remaining_amount'=>$request->initial_amount,
                'status'=>$request->status,
            ]);
            
            $response = [
                'message'=>'case updated successfully',
                'case' => $casee
            ];
        }

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
