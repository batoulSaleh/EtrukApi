<?php

namespace App\Http\Controllers\api\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Casee;
use App\Models\Item;
use App\Models\Caseimage;


class UsCaseController extends Controller
{
    public function index(){

        $casees=Casee::with('item','caseimage')->select(
            'id',
            'name_'.app()->getLocale().' as name',
            'description_'.app()->getLocale().' as description',
            'file',
            'initial_amount',
            'paied_amount',
            'remaining_amount',
            'status',
            'user_id',
            'donationtype_id',
            'category_id'
            )->with('category','donationtype','user')->where('status','published')->orWhere('status', 'completed')->get();

        $response = [
            'message'=>trans('api.fetch'),
            'cases' => $casees,
            'count' => count($casees)
        ];
        return response($response,201);
    }

    public function lastCases(){

        $casees=Casee::with('item','caseimage')->select(
            'id',
            'name_'.app()->getLocale().' as name',
            'description_'.app()->getLocale().' as description',
            'file',
            'initial_amount',
            'paied_amount',
            'remaining_amount',
            'status',
            'user_id',
            'donationtype_id',
            'category_id'
            )->with('category','donationtype','user')->where('status','published')->latest()->take(10)->get();

        $response = [
            'message'=>trans('api.fetch'),
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
            'file',
            'initial_amount',
            'paied_amount',
            'remaining_amount',
            'status',
            'user_id',
            'donationtype_id',
            'category_id'
            )->with('category','donationtype','user','caseimage')->where('id',$id)->first();

            $items=Item::select(
                'id',
                'name_'.app()->getLocale().' as name',
                'amount',
                'casee_id'
                )->where('casee_id',$casee->id)->get();   
                
                $response = [
                    'message'=>trans('api.fetch'),
                    'case' => $casee,
                    'items'=>$items,
                ];

        return response($response,201);
    }
    public function showUpdate($id)
    {
        $casee=Casee::where('id',$id)->with('category','donationtype','user','item','caseimage')->first();
        $response = [
            'message'=>trans('api.fetch'),
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
        ],[
            'name_en.required'=> trans('api.required'),
            'name_ar.required'=> trans('api.required'),
            'donationtype_id.required'=> trans('api.required'),
            'category_id.required'=> trans('api.required'),
            'donationtype_id.exists'=> trans('api.exists'),
            'category_id.exists'=> trans('api.exists'),
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
            ],[
            'items.required'=> trans('api.required'),
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
                'user_id'=>$request->user()->id,
                'status'=>'pending'
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
                'message'=>trans('api.stored'),
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
            ],[
                'description_en.required'=> trans('api.required'),
                'description_ar.required'=> trans('api.required'),
                'type_en.required'=> trans('api.required'),
                'type_ar.required'=> trans('api.required'),
                'gender_en.required'=> trans('api.required'),
                'gender_ar.required'=> trans('api.required'),
                'initial_amount.required'=> trans('api.required'),
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
                'user_id'=>$request->user()->id,
                'status'=>'pending'
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
                'message'=>trans('api.stored'),
                'case' => $casee
            ];
        }else{
            $request->validate([
                'initial_amount'=>'required|numeric',
            ],[
                'initial_amount.required'=> trans('api.required'),
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
                'user_id'=>$request->user()->id,
                'status'=>'pending'
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
                'message'=>trans('api.stored'),
                'case' => $casee
            ];
        }
        return response($response,201);
    }


    public function update(Request $request,$id){
        $casee=Casee::find($id);

        if($casee->user_id==$request->user()->id){
            if($casee->status=='pending'){
                $request->validate([
                    'status'=>'required|in:pending'
                ],[
                    'status.required'=> trans('api.required'),
                ]);
            }
            else{
                $request->validate([
                    'status'=>'required|in:accepted,published'
                ],[
                    'status.required'=> trans('api.required'),
                ]);
            }

            $request->validate([
                'donationtype_id' =>'required|exists:donationtypes,id',
            ],[
                'donationtype_id.required'=> trans('api.required'),
                'donationtype_id.exists'=> trans('api.exists'),
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
                    'gender_en' => null,
                    'gender_ar' => null,
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
                    'items'=>'required'
                ],[
                    'name_en.required'=> trans('api.required'),
                    'name_ar.required'=> trans('api.required'),
                    'category_id.required'=> trans('api.required'),
                    'category_id.exists'=> trans('api.exists'),
                    'items.required'=> trans('api.required'),
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
                        'user_id'=>$request->user()->id,
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
                    'initial_amount'=>'required|numeric',
                ],[
                    'name_en.required'=> trans('api.required'),
                    'name_ar.required'=> trans('api.required'),
                    'description_en.required'=> trans('api.required'),
                    'description_ar.required'=> trans('api.required'),
                    'type_en.required'=> trans('api.required'),
                    'type_ar.required'=> trans('api.required'),
                    'gender_en.required'=> trans('api.required'),
                    'gender_ar.required'=> trans('api.required'),
                    'category_id.required'=> trans('api.required'),
                    'category_id.exists'=> trans('api.exists'),
                    'initial_amount.required'=> trans('api.required'),
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
                    'user_id'=>$request->user()->id,
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
                    'message'=>'case created successfully',
                    'case' => $casee
                ];

            }else{
                $request->validate([
                    'name_en' => 'required|string|max:200',
                    'name_ar' => 'required|string|max:200',
                    'description_en' => 'string|max:500',
                    'description_ar' => 'string|max:500',
                    'file' => 'file|max:2048',
                    'category_id' =>'required|exists:categories,id',
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
                    'user_id'=>$request->user()->id,
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
            'initial_amount',
            'paied_amount',
            'remaining_amount',
            'status',
            'user_id',
            'donationtype_id',
            'category_id'
            )->with('category','donationtype','user','item','caseimage')->where('status','published')->where('category_id',$categoryid)->get();

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
            'initial_amount',
            'paied_amount',
            'remaining_amount',
            'status',
            'user_id',
            'donationtype_id',
            'category_id'
            )->with('category','donationtype','user','item','caseimage')->where('status','published')->where('donationtype_id',$donationtypeid)->get();

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
            'initial_amount',
            'paied_amount',
            'remaining_amount',
            'status',
            'user_id',
            'donationtype_id',
            'category_id'
            )->with('category','donationtype','user','item','caseimage')->where('status','published')->where('category_id',$categoryid)->where('donationtype_id',$donationtypeid)->get();

        $response = [
            'message'=>'All cases of category and donation type',
            'cases' => $casees
        ];
        return response($response,201);
    }
}
