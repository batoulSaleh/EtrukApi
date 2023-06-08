<?php

namespace App\Http\Controllers\api\user;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Donation;
use App\Models\Casee;
use App\Models\Item;
use App\Models\Donationitem;
use App\Models\Donationtype;
use App\Models\Payment;

class UsDonationController extends Controller
{
    public function index(){
        $donations=Donation::where('status','accepted')->get();
        $response = [
            'message'=>trans('api.fetch'),
            'donations' => $donations,
            'count' => count($donations)
        ];
        return response($response,201);
    }

    public function indexfinancial(){
        $donations=Donation::where('status','accepted')->where('donationtype_id','1')->get();
        $response = [
            'message'=>trans('api.fetch'),
            'donations' => $donations,
            'count' => count($donations)
        ];
        return response($response,201);
    }

    public function donationTypes(){
        $donationtypes=Donationtype::select(
            'id',
            'name_'.app()->getLocale().' as name',
            )->get();
        $response = [
            'message'=>trans('api.fetch'),
            'Donationtypes' => $donationtypes
        ];
        return response($response,201);
    }

    public function getmoney(){
        $donations=Donation::where('donationtype_id','1')->where('status','accepted')->get();
        $sum=0;
        foreach($donations as $donation){
            $sum=$sum+$donation->amount;
        }
        $response = [
            'message'=>trans('api.fetch'),
            'sum' => $sum,
            'count'=>count($donations)
        ];
        return response($response,201);
    }

    public function indexOfUser(Request $request){
        $donations=Donation::where('user_id',$request->user()->id)->get();
        $response = [
            'message'=>trans('api.fetch'),
            'donations' => $donations
        ];
        return response($response,201);
    }

    public function show($id)
    {
        $donation=Donation::find($id);
        $response = [
            'message'=>trans('api.fetch'),
            'donation' => $donation
        ];
        return response($response,201);
    }

    public function donatefinanciallyUser(Request $request){
        $fields=$request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'amount_financial' => 'required|numeric',
            'amount_description' => 'string',
            'method' => 'in:online_payment,representative,vodafone',
            'casee_id' => 'required|exists:casees,id',
            'donationtype_id' => 'required|exists:donationtypes,id',
            'address' => 'string',
            'date_to_send' => 'date'
        ],[
            'name.required'=> trans('api.required'),
            'name.string'=> trans('api.string'),
            'email.required'=> trans('api.required'),
            'email.email'=> trans('api.email'),
            'phone.required'=> trans('api.required'),
            'phone.numeric'=> trans('api.numeric'),
            'amount_financial.required'=> trans('api.required'),
            'amount_financial.numeric'=> trans('api.numeric'),
            'amount_description.string'=> trans('api.string'),
            'method.in'=> trans('api.exists'),
            'casee_id.required'=> trans('api.required'),
            'donationtype_id.required'=> trans('api.required'),
            'casee_id.exists'=> trans('api.exists'),
            'donationtype_id.exists'=> trans('api.exists'),
            'address.string'=> trans('api.string'),
        ]);

        

        if($request->method=='representative'){
            $request->validate([
            'address' => 'required|string',
            'date_to_send' => 'required|date'
            ],[
                'address.required'=> trans('api.required'),
                'date_to_send.required'=> trans('api.required'),
                'address.string'=> trans('api.string'),
            ]);
        }

        $case=Casee::find($fields['casee_id']);
        if($fields['donationtype_id']!=$case->donationtype_id){
            $response = [
                'message'=>trans('api.diffdonation'),
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
            'message'=>trans('api.stored'),
            'donation' => $donation
        ];
        return response($response,201);
    }

    public function donatefinanciallyUserC(Request $request){
        $fields=$request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'amount_financial' => 'required|numeric',
            'amount_description' => 'string',
            'method' => 'in:online_payment,representative,vodafone',
            'casee_id' => 'required|exists:casees,id',
            'donationtype_id' => 'required|exists:donationtypes,id',
            'address' => 'string',
            'date_to_send' => 'date'
        ],[
            'name.required'=> trans('api.required'),
            'name.string'=> trans('api.string'),
            'email.required'=> trans('api.required'),
            'email.email'=> trans('api.email'),
            'phone.required'=> trans('api.required'),
            'phone.numeric'=> trans('api.numeric'),
            'amount_financial.required'=> trans('api.required'),
            'amount_financial.numeric'=> trans('api.numeric'),
            'amount_description.string'=> trans('api.string'),
            'method.in'=> trans('api.exists'),
            'casee_id.required'=> trans('api.required'),
            'donationtype_id.required'=> trans('api.required'),
            'casee_id.exists'=> trans('api.exists'),
            'donationtype_id.exists'=> trans('api.exists'),
            'address.string'=> trans('api.string'),
        ]);

        

        if($request->method=='representative'){
            $request->validate([
            'address' => 'required|string',
            'date_to_send' => 'required|date'
            ],[
                'address.required'=> trans('api.required'),
                'date_to_send.required'=> trans('api.required'),
                'address.string'=> trans('api.string'),
            ]);
        }

        $case=Casee::find($fields['casee_id']);
        if($fields['donationtype_id']!=$case->donationtype_id){
            $response = [
                'message'=>trans('api.diffdonation'),
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

        $return ="https://etruk-athra.invoacdmy.com/card-details/".$case->id."?status=1";
        $payment_response = Http::withHeaders([
            'authorization' => 'SGJNZBM2ZT-JGW9G2JHDW-N29ZZHK9JH',
            'Content-Type' => 'application/json',
        ])
        ->post('https://secure-egypt.paytabs.com/payment/request', [
            "profile_id" => "79010",
            "tran_type" => "sale",
            "tran_class" =>  "ecom",
            "cart_id" => "CART#1001",
            "cart_currency" =>  "USD",
            "cart_amount" =>  $request->amount_financial,
            "cart_description" =>  "Description of the items/services",
            "return"=>$return,
        ]);

        $response = [
            'message'=>trans('api.stored'),
            'donation' => $donation,
            'payment_response'=>$payment_response->json()
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
        ],[
            'name.required'=> trans('api.required'),
            'name.string'=> trans('api.string'),
            'email.required'=> trans('api.required'),
            'email.email'=> trans('api.email'),
            'phone.required'=> trans('api.required'),
            'phone.numeric'=> trans('api.numeric'),
            'amount_financial.required'=> trans('api.required'),
            'amount_financial.numeric'=> trans('api.numeric'),
            'amount_description.string'=> trans('api.string'),
            'method.in'=> trans('api.exists'),
            'casee_id.required'=> trans('api.required'),
            'donationtype_id.required'=> trans('api.required'),
            'casee_id.exists'=> trans('api.exists'),
            'donationtype_id.exists'=> trans('api.exists'),
            'address.string'=> trans('api.string'),
        ]);

        if($request->method=='representative'){
            $request->validate([
            'address' => 'required|string',
            'date_to_send' => 'required|date'
            ],[
                'address.required'=> trans('api.required'),
                'date_to_send.required'=> trans('api.required'),
                'address.string'=> trans('api.string'),
            ]);
        }
        $case=Casee::find($fields['casee_id']);
        if($fields['donationtype_id']!=$case->donationtype_id){
            $response = [
                'message'=>trans('api.diffdonation'),
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
            'message'=>trans('api.stored'),
            'donation' => $donation
        ];
        return response($response,201);
    }

    public function volunteeringUser(Request $request){
        $fields=$request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'city' => 'required|string',
            'amount' => 'required|numeric',
            'description'=>'string',
            'casee_id' => 'required|exists:casees,id',
            'donationtype_id' => 'required|exists:donationtypes,id',
            'address' => 'string',
        ],[
            'name.required'=> trans('api.required'),
            'name.string'=> trans('api.string'),
            'email.required'=> trans('api.required'),
            'email.email'=> trans('api.email'),
            'phone.required'=> trans('api.required'),
            'phone.numeric'=> trans('api.numeric'),
            'city.required'=> trans('api.required'),
            'city.string'=> trans('api.string'),
            'amount.required'=> trans('api.required'),
            'amount.numeric'=> trans('api.numeric'),
            'description.string'=> trans('api.string'),
            'casee_id.required'=> trans('api.required'),
            'donationtype_id.required'=> trans('api.required'),
            'casee_id.exists'=> trans('api.exists'),
            'donationtype_id.exists'=> trans('api.exists'),
            'address.string'=> trans('api.string'),
        ]);

        $case=Casee::find($fields['casee_id']);
        if($fields['donationtype_id']!=$case->donationtype_id){
            $response = [
                'message'=>trans('api.diffdonation'),
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
            'message'=>trans('api.stored'),
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
        ],[
            'name.required'=> trans('api.required'),
            'name.string'=> trans('api.string'),
            'email.required'=> trans('api.required'),
            'email.email'=> trans('api.email'),
            'phone.required'=> trans('api.required'),
            'phone.numeric'=> trans('api.numeric'),
            'city.required'=> trans('api.required'),
            'city.string'=> trans('api.string'),
            'amount.required'=> trans('api.required'),
            'amount.numeric'=> trans('api.numeric'),
            'description.string'=> trans('api.string'),
            'casee_id.required'=> trans('api.required'),
            'donationtype_id.required'=> trans('api.required'),
            'casee_id.exists'=> trans('api.exists'),
            'donationtype_id.exists'=> trans('api.exists'),
            'address.string'=> trans('api.string'),
        ]);

        
        $case=Casee::find($fields['casee_id']);
        if($fields['donationtype_id']!=$case->donationtype_id){
            $response = [
                'message'=>trans('api.diffdonation'),
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
            'message'=>trans('api.stored'),
            'donation' => $donation
        ];
        return response($response,201);
    }

    
    public function foodUser(Request $request){
        $fields=$request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'city' => 'required|string',
            'amount' => 'required|numeric',
            'amount_description' => 'string',
            'description'=>'string',
            'method' => 'in:representative',
            'casee_id' => 'required|exists:casees,id',
            'donationtype_id' => 'required|exists:donationtypes,id',
            'address' => 'required|string',
            'date_to_send' => 'required|date'
        ],[
            'name.required'=> trans('api.required'),
            'name.string'=> trans('api.string'),
            'email.required'=> trans('api.required'),
            'email.email'=> trans('api.email'),
            'phone.numeric'=> trans('api.numeric'),
            'phone.required'=> trans('api.required'),
            'city.required'=> trans('api.required'),
            'city.string'=> trans('api.string'),
            'amount.required'=> trans('api.required'),
            'amount.numeric'=> trans('api.numeric'),
            'amount_description.string'=> trans('api.string'),
            'method.in'=> trans('api.exists'),
            'casee_id.required'=> trans('api.required'),
            'donationtype_id.required'=> trans('api.required'),
            'casee_id.exists'=> trans('api.exists'),
            'donationtype_id.exists'=> trans('api.exists'),
            'address.required'=> trans('api.required'),
            'address.string'=> trans('api.string'),
            'date_to_send.required'=> trans('api.required'),
        ]);

        $case=Casee::find($fields['casee_id']);
        if($fields['donationtype_id']!=$case->donationtype_id){
            $response = [
                'message'=>trans('api.diffdonation'),
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
            'message'=>trans('api.stored'),
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
            'address' => 'required|string',
            'date_to_send' => 'required|date'
        ],[
            'name.required'=> trans('api.required'),
            'name.string'=> trans('api.string'),
            'email.required'=> trans('api.required'),
            'email.email'=> trans('api.email'),
            'phone.numeric'=> trans('api.numeric'),
            'phone.required'=> trans('api.required'),
            'city.required'=> trans('api.required'),
            'city.string'=> trans('api.string'),
            'amount.required'=> trans('api.required'),
            'amount.numeric'=> trans('api.numeric'),
            'amount_description.string'=> trans('api.string'),
            'method.in'=> trans('api.exists'),
            'casee_id.required'=> trans('api.required'),
            'donationtype_id.required'=> trans('api.required'),
            'casee_id.exists'=> trans('api.exists'),
            'donationtype_id.exists'=> trans('api.exists'),
            'address.required'=> trans('api.required'),
            'address.string'=> trans('api.string'),
            'date_to_send.required'=> trans('api.required'),
        ]);

        $case=Casee::find($fields['casee_id']);
        if($fields['donationtype_id']!=$case->donationtype_id){
            $response = [
                'message'=>trans('api.diffdonation'),
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
            'message'=>trans('api.stored'),
            'donation' => $donation
        ];
        return response($response,201);
    }

    public function clothesUser(Request $request){
        $fields=$request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'city' => 'required|string',
            'method' => 'in:representative',
            'amount' => 'required|numeric',
            'amount_description' => 'string',
            'casee_id' => 'required|exists:casees,id',
            'donationtype_id' => 'required|exists:donationtypes,id',
            'address' => 'required|string',
            'description'=>'string',
            'date_to_send' => 'required|date'
        ],[
            'name.required'=> trans('api.required'),
            'name.string'=> trans('api.string'),
            'email.required'=> trans('api.required'),
            'email.email'=> trans('api.email'),
            'phone.numeric'=> trans('api.numeric'),
            'phone.required'=> trans('api.required'),
            'city.required'=> trans('api.required'),
            'city.string'=> trans('api.string'),
            'amount.required'=> trans('api.required'),
            'amount.numeric'=> trans('api.numeric'),
            'amount_description.string'=> trans('api.string'),
            'method.in'=> trans('api.exists'),
            'casee_id.required'=> trans('api.required'),
            'donationtype_id.required'=> trans('api.required'),
            'casee_id.exists'=> trans('api.exists'),
            'donationtype_id.exists'=> trans('api.exists'),
            'address.required'=> trans('api.required'),
            'address.string'=> trans('api.string'),
            'date_to_send.required'=> trans('api.required'),
        ]);

        
        $case=Casee::find($fields['casee_id']);
        if($fields['donationtype_id']!=$case->donationtype_id){
            $response = [
                'message'=>trans('api.diffdonation'),
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
            'message'=>trans('api.stored'),
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
            'address' => 'required|string',
            'description'=>'string',
            'date_to_send' => 'required|date'
        ],[
            'name.required'=> trans('api.required'),
            'name.string'=> trans('api.string'),
            'email.required'=> trans('api.required'),
            'email.email'=> trans('api.email'),
            'phone.numeric'=> trans('api.numeric'),
            'phone.required'=> trans('api.required'),
            'city.required'=> trans('api.required'),
            'city.string'=> trans('api.string'),
            'amount.required'=> trans('api.required'),
            'amount.numeric'=> trans('api.numeric'),
            'amount_description.string'=> trans('api.string'),
            'method.in'=> trans('api.exists'),
            'casee_id.required'=> trans('api.required'),
            'donationtype_id.required'=> trans('api.required'),
            'casee_id.exists'=> trans('api.exists'),
            'donationtype_id.exists'=> trans('api.exists'),
            'address.required'=> trans('api.required'),
            'address.string'=> trans('api.string'),
            'date_to_send.required'=> trans('api.required'),
        ]);

        $case=Casee::find($fields['casee_id']);
        if($fields['donationtype_id']!=$case->donationtype_id){
            $response = [
                'message'=>trans('api.diffdonation'),
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
            'message'=>trans('api.stored'),
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
            'address' => 'required|string',
            'description'=>'string',
            'date_to_send' => 'required|date',
            'items'=>'required'
        ],[
            'name.required'=> trans('api.required'),
            'name.string'=> trans('api.string'),
            'email.required'=> trans('api.required'),
            'email.email'=> trans('api.email'),
            'phone.numeric'=> trans('api.numeric'),
            'phone.required'=> trans('api.required'),
            'city.required'=> trans('api.required'),
            'city.string'=> trans('api.string'),
            'method.in'=> trans('api.exists'),
            'casee_id.required'=> trans('api.required'),
            'donationtype_id.required'=> trans('api.required'),
            'casee_id.exists'=> trans('api.exists'),
            'donationtype_id.exists'=> trans('api.exists'),
            'address.required'=> trans('api.required'),
            'address.string'=> trans('api.string'),
            'date_to_send.required'=> trans('api.required'),
            'items.required'=> trans('api.required'),
        ]);

        $case=Casee::find($fields['casee_id']);
        if($fields['donationtype_id']!=$case->donationtype_id){
            $response = [
                'message'=>trans('api.diffdonation'),
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
            'message'=>trans('api.stored'),
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
            'address' => 'required|string',
            'description'=>'string',
            'date_to_send' => 'required|date',
            'items'=>'required'
        ],[
            'name.required'=> trans('api.required'),
            'name.string'=> trans('api.string'),
            'email.required'=> trans('api.required'),
            'email.email'=> trans('api.email'),
            'phone.numeric'=> trans('api.numeric'),
            'phone.required'=> trans('api.required'),
            'city.required'=> trans('api.required'),
            'city.string'=> trans('api.string'),
            'method.in'=> trans('api.exists'),
            'casee_id.required'=> trans('api.required'),
            'donationtype_id.required'=> trans('api.required'),
            'casee_id.exists'=> trans('api.exists'),
            'donationtype_id.exists'=> trans('api.exists'),
            'address.required'=> trans('api.required'),
            'address.string'=> trans('api.string'),
            'date_to_send.required'=> trans('api.required'),
            'items.required'=> trans('api.required'),
        ]);


        $case=Casee::find($fields['casee_id']);
        if($fields['donationtype_id']!=$case->donationtype_id){
            $response = [
                'message'=>trans('api.diffdonation'),
            ];
            return response($response,500);
        }

        $items=$request->items;
        $total_amount=0;

        foreach($items as $item){
            $it=Item::find($item['id']);
            if((double)$item['amount']>(double)$it->amount){
                $response = [
                    'message'=>'the '.$it->name_en .' amount must be less than or equal' . $it->amount,
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
            'message'=>trans('api.stored'),
            'donation' => $donation
        ];
        return response($response,201);
    }
    
    public function storePayment(Request $request){
        $request->validate([
            'cnn' => 'required|numeric',
            'name' => 'required|string|max:200',
            'date' => 'required|date',
            'verification_code' => 'required|string|max:500',
            'donation_id' => 'required|exists:donations,id',
        ]);

        $payment = Payment::create([
            'cnn' => $request->cnn,
            'name'=> $request->name,
            'date'=> $request->date,
            'verification_code'=> $request->verification_code,
            'donation_id'=> $request->donation_id,
        ]);

        $response = [
            'message'=>'payment created successfully',
            'payment' => $payment
        ];
        return response($response,201);
    }

}
