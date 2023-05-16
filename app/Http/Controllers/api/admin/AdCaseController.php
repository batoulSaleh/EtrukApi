<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Casee;
use App\Models\Item;
use App\Models\Donationitem;
use App\Models\Caseimage;

class AdCaseController extends Controller
{
    public function index(){
        $casees=Casee::with('item','caseimage')->get();
        $response = [
            'message'=>'All cases',
            'cases' => $casees,
            'count' => count($casees)
        ];
        return response($response,201);
    }

    public function show($id)
    {
        $casee=Casee::where('id',$id)->with('category','donationtype','user','item','caseimage')->first();
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
            'file' => 'file|max:2048',
            'donationtype_id' =>'required|exists:donationtypes,id',
            'category_id' =>'required|exists:categories,id',
            'status'=>'required|in:pending,accepted,published,rejected',
        ]);

        if($request->file('file')){
            $file_path = $request->file('file')->store('api/casees','public');
            $file=asset('storage/'.$file_path);
        }else{
            $file=null;
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
                'file' => $file,
                'donationtype_id'=> $request->donationtype_id,
                'category_id'=> $request->category_id,
                'initial_amount'=>$initial_amount,
                'paied_amount'=>0,
                'remaining_amount'=>$initial_amount,
                'status'=>$request->status,
                'user_id'=>1
            ]);

            $images=$request->file('images');
            if($images){
                foreach($images as $image){
                    $image_path = $image->store('api/casees','public');
                    $image=asset('storage/'.$image_path);
                
    
                    Caseimage::create([
                        'casee_id'=>$casee->id,
                        'image'=>$image
                    ]);
                }
            }
    
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
                'gender_en' => 'required|string|max:500',
                'gender_ar' => 'required|string|max:500',
                'initial_amount'=>'required|numeric',
            ]);

            $casee = Casee::create([
                'name_en' => $request->name_en,
                'name_ar'=> $request->name_ar,
                'description_en'=> $request->description_en,
                'description_ar'=> $request->description_ar,
                'type_en' => $request->type_en,
                'type_ar' => $request->type_ar,
                'gender_en' => $request->gender_en,
                'gender_ar' => $request->gender_ar,
                'file' => $file,
                'donationtype_id'=> $request->donationtype_id,
                'category_id'=> $request->category_id,
                'initial_amount'=>$request->initial_amount,
                'paied_amount'=>0,
                'remaining_amount'=>$request->initial_amount,
                'status'=>$request->status,
                'user_id'=>1
            ]);
            $images=$request->file('images');
            if($images){
                foreach($images as $image){
                    $image_path = $image->store('api/casees','public');
                    $image=asset('storage/'.$image_path);
                

                    Caseimage::create([
                        'casee_id'=>$casee->id,
                        'image'=>$image
                    ]);
                }
            }
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
            'file' => $file,
            'donationtype_id'=> $request->donationtype_id,
            'category_id'=> $request->category_id,
            'initial_amount'=>$request->initial_amount,
            'paied_amount'=>0,
            'remaining_amount'=>$request->initial_amount,
            'status'=>$request->status,
            'user_id'=>1
        ]);

        $images=$request->file('images');
        if($images){
            foreach($images as $image){
                $image_path = $image->store('api/casees','public');
                $image=asset('storage/'.$image_path);
            

                Caseimage::create([
                    'casee_id'=>$casee->id,
                    'image'=>$image
                ]);
            }
        }

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
                'file' => 'file|max:2048',
                'category_id' =>'required|exists:categories,id',
                'status'=>'required|in:pending,accepted,published,rejected',
                'items'=>'required'
            ]);

            if($request->file('file')){
                $file_path = $request->file('file')->store('api/casees','public');
                $file=asset('storage/'.$file_path);
            }else{
                $file=$casee->file;
            }

            $initial_amount=0;
            $items=$request->items;

            $casee->update([
                    'name_en' => $request->name_en,
                    'name_ar'=> $request->name_ar,
                    'description_en'=> $request->description_en,
                    'description_ar'=> $request->description_ar,
                    'file' => $file,
                    'donationtype_id'=> $request->donationtype_id,
                    'category_id'=> $request->category_id,
                    'initial_amount'=>$initial_amount,
                    'remaining_amount'=>$initial_amount,
                    'status'=>$request->status,
                    'reason_reject_en'=>$request->reason_reject_en,
                    'reason_reject_ar'=>$request->reason_reject_ar,
                ]);
            
            $images=$request->file('images');
            if($images){
                $old_images=Caseimage::where('casee_id',$casee->id)->get();
                foreach($old_images as $old_image){
                    $old_image->delete();
                }                
                    foreach($images as $image){
                        $image_path = $image->store('api/casees','public');
                        $image=asset('storage/'.$image_path);
                        Caseimage::create([
                            'casee_id'=>$casee->id,
                            'image'=>$image
                        ]);
                    }
            }
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
                'gender_en' => 'required|string|max:500',
                'gender_ar' => 'required|string|max:500',
                'file' => 'file|max:2048',
                'category_id' =>'required|exists:categories,id',
                'status'=>'required|in:pending,accepted,published,rejected',
                'initial_amount'=>'required|numeric',
            ]);
            if($request->file('file')){
                $file_path = $request->file('file')->store('api/casees','public');
                $file=asset('storage/'.$file_path);
            }else{
                $file=$casee->file;
            }
            $casee->update([
                'name_en' => $request->name_en,
                'name_ar'=> $request->name_ar,
                'description_en'=> $request->description_en,
                'description_ar'=> $request->description_ar,
                'type_en' => $request->type_en,
                'type_ar' => $request->type_ar,
                'gender_en' => $request->gender_en,
                'gender_ar' => $request->gender_ar,
                'file' => $file,
                'donationtype_id'=> $request->donationtype_id,
                'category_id'=> $request->category_id,
                'initial_amount'=>$request->initial_amount,
                'remaining_amount'=>$request->initial_amount,
                'status'=>$request->status,
                'reason_reject_en'=>$request->reason_reject_en,
                'reason_reject_ar'=>$request->reason_reject_ar,
            ]);

            $images=$request->file('images');
            if($images){
                $old_images=Caseimage::where('casee_id',$casee->id)->get();
                foreach($old_images as $old_image){
                    $old_image->delete();
                }                
                    foreach($images as $image){
                        $image_path = $image->store('api/casees','public');
                        $image=asset('storage/'.$image_path);
                        Caseimage::create([
                            'casee_id'=>$casee->id,
                            'image'=>$image
                        ]);
                    }
            }
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
                'file' => 'file|max:2048',
                'category_id' =>'required|exists:categories,id',
                'status'=>'required|in:pending,accepted,published,rejected',
                'initial_amount'=>'required|numeric',
            ]);
            if($request->file('file')){
                $file_path = $request->file('file')->store('api/casees','public');
                $file=asset('storage/'.$file_path);
            }else{
                $file=$casee->file;
            }
            $casee->update([
                'name_en' => $request->name_en,
                'name_ar'=> $request->name_ar,
                'description_en'=> $request->description_en,
                'description_ar'=> $request->description_ar,
                'file' => $file,
                'donationtype_id'=> $request->donationtype_id,
                'category_id'=> $request->category_id,
                'initial_amount'=>$request->initial_amount,
                'remaining_amount'=>$request->initial_amount,
                'status'=>$request->status,
                'reason_reject_en'=>$request->reason_reject_en,
                'reason_reject_ar'=>$request->reason_reject_ar,
            ]);
            $images=$request->file('images');
            if($images){
                $old_images=Caseimage::where('casee_id',$casee->id)->get();
                foreach($old_images as $old_image){
                    $old_image->delete();
                }
                    foreach($images as $image){
                        $image_path = $image->store('api/casees','public');
                        $image=asset('storage/'.$image_path);
                        Caseimage::create([
                            'casee_id'=>$casee->id,
                            'image'=>$image
                        ]);
                    }
            }
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
