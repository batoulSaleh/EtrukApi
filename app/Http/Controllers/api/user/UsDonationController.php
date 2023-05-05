<?php

namespace App\Http\Controllers\api\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Donation;
use App\Models\Casee;
use App\Models\Item;
use App\Models\Donationitem;

class UsDonationController extends Controller
{
    public function index(){
        $donations=Donation::all();
        $response = [
            'message'=>'All donations',
            'donations' => $donations
        ];
        return response($response,201);
    }

    public function indexOfUser(Request $request){
        $donations=Donation::where('user_id',$request->user()->id)->get();
        $response = [
            'message'=>'All donations of user',
            'donations' => $donations
        ];
        return response($response,201);
    }

    public function show($id)
    {
        $donation=Donation::find($id);
        $response = [
            'message'=>'specific donation with id',
            'donation' => $donation
        ];
        return response($response,201);
    }

    public function donatefinanciallyUser(Request $request){
        $fields=$request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'amount_financial' => 'required|numeric',
            'amount_description' => 'string',
            'method' => 'in:online_payment,representative,vodafone',
            'casee_id' => 'required|exists:casees,id',
            'donationtype_id' => 'required|exists:donationtypes,id',
            'address' => 'string',
            'date_to_send' => 'date'
        ]);

        

        if($request->method=='representative'){
            $request->validate([
            'address' => 'required|string',
            'date_to_send' => 'required|date'

            ]);
        }

        $case=Casee::find($fields['casee_id']);
        if($fields['donationtype_id']!=$case->donationtype_id){
            $response = [
                'message'=>'different donationtypes',
            ];
            return response($response,500);
        }

        $donation = Donation::create([
            'casee_id' => $request->casee_id,
            'donationtype_id' => $request->donationtype_id,
            'method' => $request->method,
            'name' => $request->name,
            'email' => $request->email,
            'amount' => $request->amount_financial,
            'amount_description' => $request->amount_description,
            'address' => $request->address,
            'date_to_send' => $request->date_to_send,
            'user_id' => $request->user()->id,
            'phone'=>$request->phone,
            'status'=>'pending'
        ]);

        $response = [
            'message'=>'donation created successfully',
            'donation' => $donation
        ];
        return response($response,201);
    }

    public function donatefinanciallyGuest(Request $request){
        $fields=$request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'amount_financial' => 'required|numeric',
            'amount_description' => 'string',
            'method' => 'in:online_payment,representative,vodafone',
            'casee_id' => 'required|exists:casees,id',
            'donationtype_id' => 'required|exists:donationtypes,id',
            'address' => 'string',
            'date_to_send' => 'date'
        ]);

        

        if($request->method=='representative'){
            $request->validate([
            'address' => 'required|string',
            'date_to_send' => 'required|date'

            ]);
        }
        $case=Casee::find($fields['casee_id']);
        if($fields['donationtype_id']!=$case->donationtype_id){
            $response = [
                'message'=>'different donationtypes',
            ];
            return response($response,500);
        }
        

        $donation = Donation::create([
            'casee_id' => $request->casee_id,
            'donationtype_id' => $request->donationtype_id,
            'method' => $request->method,
            'name' => $request->name,
            'email' => $request->email,
            'amount' => $request->amount_financial,
            'phone'=>$request->phone,
            'amount_description' => $request->amount_description,
            'address' => $request->address,
            'date_to_send' => $request->date_to_send,
            'status'=>'pending'
        ]);

        $response = [
            'message'=>'donation created successfully',
            'donation' => $donation
        ];
        return response($response,201);
    }

    public function volunteeringUser(Request $request){
        $fields=$request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'city' => 'required|string',
            'amount' => 'required|numeric',
            'description'=>'string',
            'casee_id' => 'required|exists:casees,id',
            'donationtype_id' => 'required|exists:donationtypes,id',
            'address' => 'string',
        ]);

        $case=Casee::find($fields['casee_id']);
        if($fields['donationtype_id']!=$case->donationtype_id){
            $response = [
                'message'=>'different donationtypes',
            ];
            return response($response,500);
        }
        
        $donation = Donation::create([
            'casee_id' => $request->casee_id,
            'donationtype_id' => $request->donationtype_id,
            'name' => $request->name,
            'email' => $request->email,
            'amount' => $request->amount,
            'phone'=>$request->phone,
            'city' => $request->city,
            'description'=>$request->description,
            'address' => $request->address,
            'user_id' => $request->user()->id,
            'status'=>'pending'
        ]);

        $response = [
            'message'=>'donation created successfully',
            'donation' => $donation
        ];
        return response($response,201);
    }

    public function volunteeringGuest(Request $request){
        $fields=$request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'city' => 'required|string',
            'amount' => 'required|numeric',
            'description'=>'string',
            'casee_id' => 'required|exists:casees,id',
            'donationtype_id' => 'required|exists:donationtypes,id',
            'address' => 'string',
        ]);

        
        $case=Casee::find($fields['casee_id']);
        if($fields['donationtype_id']!=$case->donationtype_id){
            $response = [
                'message'=>'different donationtypes',
            ];
            return response($response,500);
        }
        
        $donation = Donation::create([
            'casee_id' => $request->casee_id,
            'donationtype_id' => $request->donationtype_id,
            'name' => $request->name,
            'email' => $request->email,
            'amount' => $request->amount,
            'phone'=>$request->phone,
            'city' => $request->city,
            'description'=>$request->description,
            'address' => $request->address,
            'status'=>'pending'
        ]);

        $response = [
            'message'=>'donation created successfully',
            'donation' => $donation
        ];
        return response($response,201);
    }

    
    public function foodUser(Request $request){
        $fields=$request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'city' => 'required|string',
            'amount' => 'required|numeric',
            'amount_description' => 'string',
            'description'=>'string',
            'method' => 'in:representative',
            'casee_id' => 'required|exists:casees,id',
            'donationtype_id' => 'required|exists:donationtypes,id',
            'address' => 'string',
            'date_to_send' => 'date'
        ]);

        

        if($request->method=='representative'){
            $request->validate([
            'address' => 'required|string',
            'date_to_send' => 'required|date'

            ]);
        }
        $case=Casee::find($fields['casee_id']);
        if($fields['donationtype_id']!=$case->donationtype_id){
            $response = [
                'message'=>'different donationtypes',
            ];
            return response($response,500);
        }
        
        $donation = Donation::create([
            'casee_id' => $request->casee_id,
            'donationtype_id' => $request->donationtype_id,
            'method' => $request->method,
            'name' => $request->name,
            'email' => $request->email,
            'amount' => $request->amount,
            'description' => $request->description,
            'amount_description' => $request->amount_description,
            'city' =>$request->city,
            'phone'=>$request->phone,
            'address' => $request->address,
            'date_to_send' => $request->date_to_send,
            'user_id' => $request->user()->id,
            'status'=>'pending'
        ]);

        $response = [
            'message'=>'donation created successfully',
            'donation' => $donation
        ];
        return response($response,201);
    }

    public function foodGuest(Request $request){
        $fields=$request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'city' => 'required|string',
            'method' => 'in:representative',
            'amount' => 'required|numeric',
            'amount_description' => 'string',
            'description'=>'string',
            'casee_id' => 'required|exists:casees,id',
            'donationtype_id' => 'required|exists:donationtypes,id',
            'address' => 'string',
            'date_to_send' => 'date'
        ]);

        

        if($request->method=='representative'){
            $request->validate([
            'address' => 'required|string',
            'date_to_send' => 'required|date'

            ]);
        }
        $case=Casee::find($fields['casee_id']);
        if($fields['donationtype_id']!=$case->donationtype_id){
            $response = [
                'message'=>'different donationtypes',
            ];
            return response($response,500);
        }
        
        $donation = Donation::create([
            'casee_id' => $request->casee_id,
            'donationtype_id' => $request->donationtype_id,
            'method' => $request->method,
            'name' => $request->name,
            'email' => $request->email,
            'amount' => $request->amount,
            'description' => $request->description,
            'amount_description' => $request->amount_description,
            'city' =>$request->city,
            'phone'=>$request->phone,
            'address' => $request->address,
            'date_to_send' => $request->date_to_send,
            'status'=>'pending'
        ]);

        $response = [
            'message'=>'donation created successfully',
            'donation' => $donation
        ];
        return response($response,201);
    }

    public function clothesUser(Request $request){
        $fields=$request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'city' => 'required|string',
            'method' => 'in:representative',
            'amount' => 'required|numeric',
            'amount_description' => 'string',
            'casee_id' => 'required|exists:casees,id',
            'donationtype_id' => 'required|exists:donationtypes,id',
            'address' => 'string',
            'description'=>'string',
            'date_to_send' => 'date'
        ]);

        

        if($request->method=='representative'){
            $request->validate([
            'address' => 'required|string',
            'date_to_send' => 'required|date'

            ]);
        }
        $case=Casee::find($fields['casee_id']);
        if($fields['donationtype_id']!=$case->donationtype_id){
            $response = [
                'message'=>'different donationtypes',
            ];
            return response($response,500);
        }
        
        $donation = Donation::create([
            'casee_id' => $request->casee_id,
            'donationtype_id' => $request->donationtype_id,
            'method' => $request->method,
            'name' => $request->name,
            'email' => $request->email,
            'amount' => $request->amount,
            'amount_description' => $request->amount_description,
            'phone'=>$request->phone,
            'description'=>$request->description,
            'address' => $request->address,
            'date_to_send' => $request->date_to_send,
            'user_id' => $request->user()->id,
            'status'=>'pending'
        ]);

        $response = [
            'message'=>'donation created successfully',
            'donation' => $donation
        ];
        return response($response,201);
    }

    public function clothesGuest(Request $request){
        $fields=$request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'city' => 'required|string',
            'method' => 'in:representative',
            'amount' => 'required|numeric',
            'amount_description' => 'string',
            'casee_id' => 'required|exists:casees,id',
            'donationtype_id' => 'required|exists:donationtypes,id',
            'address' => 'string',
            'description'=>'string',
            'date_to_send' => 'date'
        ]);

        

        if($request->method=='representative'){
            $request->validate([
            'address' => 'required|string',
            'date_to_send' => 'required|date'

            ]);
        }
        $case=Casee::find($fields['casee_id']);
        if($fields['donationtype_id']!=$case->donationtype_id){
            $response = [
                'message'=>'different donationtypes',
            ];
            return response($response,500);
        }
        
        $donation = Donation::create([
            'casee_id' => $request->casee_id,
            'donationtype_id' => $request->donationtype_id,
            'method' => $request->method,
            'name' => $request->name,
            'email' => $request->email,
            'amount' => $request->amount,
            'amount_description' => $request->amount_description,
            'phone'=>$request->phone,
            'description'=>$request->description,
            'address' => $request->address,
            'date_to_send' => $request->date_to_send,
            'status'=>'pending'
        ]);

        $response = [
            'message'=>'donation created successfully',
            'case' => $donation
        ];
        return response($response,201);
    }

    public function furnitureUser(Request $request){
        $fields=$request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'city' => 'required|string',
            'method' => 'in:representative',
            'casee_id' => 'required|exists:casees,id',
            'donationtype_id' => 'required|exists:donationtypes,id',
            'address' => 'string',
            'description'=>'string',
            'date_to_send' => 'date',
            'items'=>'required'
        ]);


        if($request->method=='representative'){
            $request->validate([
            'address' => 'required|string',
            'date_to_send' => 'required|date'

            ]);
        }
        $case=Casee::find($fields['casee_id']);
        if($fields['donationtype_id']!=$case->donationtype_id){
            $response = [
                'message'=>'different donationtypes',
            ];
            return response($response,500);
        }

        $items=$request->items;
        $total_amount=0;

        foreach($items as $item){
            $it=Item::find($item['id']);
            if((double)$item['amount']>(double)$it->amount){
                $response = [
                    'message'=>'the'.$it->name_en .'amount must be less than or equal' . $it->amount,
                ];
                return response($response,500);
            }
            $total_amount=$total_amount+$item['amount'];
        }
        
        $donation = Donation::create([
            'casee_id' => $request->casee_id,
            'donationtype_id' => $request->donationtype_id,
            'method' => $request->method,
            'name' => $request->name,
            'email' => $request->email,
            'amount' => $total_amount,
            'phone'=>$request->phone,
            'description'=>$request->description,
            'address' => $request->address,
            'date_to_send' => $request->date_to_send,
            'user_id' => $request->user()->id,
            'status'=>'pending'
        ]);


        foreach($items as $item){
            $donationitem=Donationitem::create([
                'item_id'=> $item['id'],
                'donation_id' =>$donation->id,
                'amount'=>$item['amount'],
            ]);
        }

        $response = [
            'message'=>'donation created successfully',
            'donation' => $donation
        ];
        return response($response,201);
    }

    public function furnitureGuest(Request $request){
        $fields=$request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'city' => 'required|string',
            'method' => 'in:representative',
            'casee_id' => 'required|exists:casees,id',
            'donationtype_id' => 'required|exists:donationtypes,id',
            'address' => 'string',
            'description'=>'string',
            'date_to_send' => 'date',
            'items'=>'required'
        ]);


        if($request->method=='representative'){
            $request->validate([
            'address' => 'required|string',
            'date_to_send' => 'required|date'

            ]);
        }
        $case=Casee::find($fields['casee_id']);
        if($fields['donationtype_id']!=$case->donationtype_id){
            $response = [
                'message'=>'different donationtypes',
            ];
            return response($response,500);
        }

        $items=$request->items;
        $total_amount=0;

        foreach($items as $item){
            $it=Item::find($item['id']);
            if((double)$item['amount']>(double)$it->amount){
                $response = [
                    'message'=>'the'.$it->name_en .'amount must be less than or equal' . $it->amount,
                ];
                return response($response,500);
            }
            $total_amount=$total_amount+$item['amount'];
        }
        
        $donation = Donation::create([
            'casee_id' => $request->casee_id,
            'donationtype_id' => $request->donationtype_id,
            'method' => $request->method,
            'name' => $request->name,
            'email' => $request->email,
            'amount' => $total_amount,
            'phone'=>$request->phone,
            'description'=>$request->description,
            'address' => $request->address,
            'date_to_send' => $request->date_to_send,
            'status'=>'pending'
        ]);


        foreach($items as $item){
            $donationitem=Donationitem::create([
                'item_id'=> $item['id'],
                'donation_id' =>$donation->id,
                'amount'=>$item['amount'],
            ]);
        }

        $response = [
            'message'=>'donation created successfully',
            'donation' => $donation
        ];
        return response($response,201);
    }
    
}
