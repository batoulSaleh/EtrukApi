<?php

namespace App\Http\Controllers\api\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Donation;
use App\Models\Casee;

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
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'amount' => 'required|numeric',
            'amount_description' => 'string',
            'method' => 'in:online_payment,representative,vodafone',
            'casee_id' => 'required|exists:casees,id',
            'donationtype_id' => 'required|exists:donationtypes,id',
            'address' => 'string',
        ]);

        

        if($request->method=='representative'){
            $request->validate([
            'address' => 'required|string',
            ]);
        }

        $case=Casee::find($donation->casee_id);
        if($donationtype_id!=$case->donationtype_id){
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
            'address' => $request->address,
            'date_to_send' => null,
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
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'amount' => 'required|numeric',
            'amount_description' => 'string',
            'method' => 'in:online_payment,representative,vodafone',
            'casee_id' => 'required|exists:casees,id',
            'donationtype_id' => 'required|exists:donationtypes,id',
            'address' => 'string',
        ]);

        

        if($request->method=='representative'){
            $request->validate([
            'address' => 'required|string',
        ]);

        }
$case=Casee::find($donation->casee_id);
        if($donationtype_id!=$case->donationtype_id){
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
            'phone'=>$request->phone,
            'amount_description' => $request->amount_description,
            'address' => $request->address,
            'date_to_send' => null,
            'status'=>'pending'
        ]);

        $response = [
            'message'=>'donation created successfully',
            'donation' => $donation
        ];
        return response($response,201);
    }

    public function volunteeringUser(Request $request){
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'city' => 'required|string',
            'casee_id' => 'required|exists:casees,id',
            'donationtype_id' => 'required|exists:donationtypes,id',
            'address' => 'string',
            'description'=>'string',
        ]);

        if($request->method=='representative'){
            $request->validate([
            'address' => 'required|string',
        ]);

        }
        $case=Casee::find($donation->casee_id);
        if($donationtype_id!=$case->donationtype_id){
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
            'amount' => 1,
            'phone'=>$request->phone,
            'description'=>$request->description,
            'address' => $request->address,
            'date_to_send' => null,
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
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'city' => 'required|string',
            'casee_id' => 'required|exists:casees,id',
            'donationtype_id' => 'required|exists:donationtypes,id',
            'address' => 'string',
            'description'=>'string',
        ]);
        if($request->method=='representative'){
            $request->validate([
            'address' => 'required|string',
        ]);

        }
        $case=Casee::find($donation->casee_id);
        if($donationtype_id!=$case->donationtype_id){
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
            'amount' => 1,
            'phone'=>$request->phone,
            'description'=>$request->description,
            'address' => $request->address,
            'date_to_send' => null,
            'status'=>'pending'
        ]);

        $response = [
            'message'=>'donation created successfully',
            'donation' => $donation
        ];
        return response($response,201);
    }

    
    public function foodUser(Request $request){
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'city' => 'required|string',
            'method' => 'in:representative',
            'amount_description' => 'string',
            'casee_id' => 'required|exists:casees,id',
            'donationtype_id' => 'required|exists:donationtypes,id',
            'address' => 'string',
        ]);
        if($request->method=='representative'){
            $request->validate([
            'address' => 'required|string',
        ]);

        }
        $case=Casee::find($donation->casee_id);
        if($donationtype_id!=$case->donationtype_id){
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
            'amount_description' => $request->amount_description,
            'phone'=>$request->phone,
            'address' => $request->address,
            'date_to_send' => null,
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
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'city' => 'required|string',
            'method' => 'in:representative',
            'amount_description' => 'string',
            'casee_id' => 'required|exists:casees,id',
            'donationtype_id' => 'required|exists:donationtypes,id',
            'address' => 'string',
        ]);
        if($request->method=='representative'){
            $request->validate([
            'address' => 'required|string',
        ]);

        }
        $case=Casee::find($donation->casee_id);
        if($donationtype_id!=$case->donationtype_id){
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
            'amount_description' => $request->amount_description,
            'phone'=>$request->phone,
            'address' => $request->address,
            'date_to_send' => null,
            'status'=>'pending'
        ]);

        $response = [
            'message'=>'donation created successfully',
            'donation' => $donation
        ];
        return response($response,201);
    }

    public function clothesUser(Request $request){
        $request->validate([
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
        ]);
        if($request->method=='representative'){
            $request->validate([
            'address' => 'required|string',
        ]);

        }
        $case=Casee::find($donation->casee_id);
        if($donationtype_id!=$case->donationtype_id){
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
            'date_to_send' => null,
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
        $request->validate([
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
        ]);
        if($request->method=='representative'){
            $request->validate([
            'address' => 'required|string',
        ]);

        }
        $case=Casee::find($donation->casee_id);
        if($donationtype_id!=$case->donationtype_id){
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
            'date_to_send' => null,
            'status'=>'pending'
        ]);

        $response = [
            'message'=>'donation created successfully',
            'case' => $donation
        ];
        return response($response,201);
    }

    public function furnitureUser(Request $request){
        $request->validate([
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
        ]);
        if($request->method=='representative'){
            $request->validate([
            'address' => 'required|string',
        ]);

        }
        $case=Casee::find($donation->casee_id);
        if($donationtype_id!=$case->donationtype_id){
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
            'date_to_send' => null,
            'user_id' => $request->user()->id,
            'status'=>'pending'
        ]);

        $response = [
            'message'=>'donation created successfully',
            'donation' => $donation
        ];
        return response($response,201);
    }

    public function furnitureGuest(Request $request){
        $request->validate([
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
        ]);
        if($request->method=='representative'){
            $request->validate([
            'address' => 'required|string',
        ]);

        }
        $case=Casee::find($donation->casee_id);
        if($donationtype_id!=$case->donationtype_id){
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
            'date_to_send' => null,
            'status'=>'pending'
        ]);

        $response = [
            'message'=>'donation created successfully',
            'donation' => $donation
        ];
        return response($response,201);
    }

    
}
