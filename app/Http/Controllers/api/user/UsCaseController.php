<?php

namespace App\Http\Controllers\api\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Casee;
use App\Models\Item;


class UsCaseController extends Controller
{
    public function index(){

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

            $items=Item::select(
                'id',
                'name_'.app()->getLocale().' as name',
                'amount',
                'casee_id'
                )->where('casee_id',$casee->id)->get();   
                
                $response = [
                    'message'=>'specific case with id',
                    'case' => $casee,
                    'items'=>$items,
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
                'user_id'=>$request->user()->id,
                'status'=>'pending'
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
                'user_id'=>$request->user()->id,
                'status'=>'pending'
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
                'user_id'=>$request->user()->id,
                'status'=>'pending'
            ]);
    
            $response = [
                'message'=>'case created successfully',
                'case' => $casee
            ];
        }

        return response($response,201);
    }


    public function update(Request $request,$id){
        if($casee->user_id==$request->user()->id){
            if($casee->status=='pending'){
                $request->validate([
                    'status'=>'required|in:pending'
                ]);
            }
            else{
                $request->validate([
                    'status'=>'required|in:accepted,published'
                ]);
            }

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
                        'user_id'=>$request->user()->id,
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
                    'user_id'=>$request->user()->id,
                ]);
                $response = [
                    'message'=>'case created successfully',
                    'case' => $casee
                ];
                
            }else{
                $request->validate([
                    'name_en' => 'required|string|max:200',
                    'name_ar' => 'required|string|max:200',
                    'description_en' => 'string|max:500',
                    'description_ar' => 'string|max:500',
                    'image' => 'image|max:2048',
                    'category_id' =>'required|exists:categories,id',
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
                    'user_id'=>$request->user()->id,
                ]);
                
                $response = [
                    'message'=>'case updated successfully',
                    'case' => $casee
                ];
            }
        }
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

    public function casesOfDonationtype($donationtypeid){
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
            )->with('category','donationtype','user')->where('status','published')->where('donationtype_id',$donationtypeid)->get();

        $response = [
            'message'=>'All cases of donation type',
            'cases' => $casees
        ];
        return response($response,201);
    }

    public function casesOfCategoryandDonationtype($categoryid,$donationtypeid){
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
            )->with('category','donationtype','user')->where('status','published')->where('category_id',$categoryid)->where('donationtype_id',$donationtypeid)->get();

        $response = [
            'message'=>'All cases of category and donation type',
            'cases' => $casees
        ];
        return response($response,201);
    }
}
