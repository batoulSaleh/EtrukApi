<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Donation;
use App\Models\Casee;
use App\Models\Item;
use App\Models\Donationitem;

class AdDonationController extends Controller
{
    public function index(){
        $donations=Donation::with('casee','donationtype')->get();
        $response = [
            'message'=>'All donations',
            'donations' => $donations
        ];
        return response($response,201);
    }

    public function indexOfCase($caseid){
        $donations=Donation::where('casee_id',$caseid)->with('casee','donationtype')->get();
        $response = [
            'message'=>'All donations of case',
            'donations' => $donations
        ];
        return response($response,201);
    }

    public function show($id)
    {
        $donation=Donation::where('id',$id)->with('casee','donationtype')->get();
        $response = [
            'message'=>'specific donation with id',
            'donation' => $donation
        ];
        return response($response,201);
    }

    public function acceptDonation($id){

        $donation=Donation::findOrFail($id);
        $donationtype_id=$donation->donationtype_id;
        $case=Casee::find($donation->casee_id);
        $method=$donation->method;

        if($donation->status!='pending'){
            $response = [
                'message'=>'the donation is already accepted',
            ];
            $code=500;
        }elseif($case->status=='completed'){
                $response = [
                    'message'=>'the case is already completed',
                ];
                $code=500;

            }elseif($donationtype_id!=$case->donationtype_id){
                $response = [
                    'message'=>'different donationtypes',
                ];
                $code=500;

            }elseif($donation->amount > $case->remaining_amount){
                $message='wrong amount,choose amount less than or equal '.$case->remaining_amount;
                $response = [
                    'message'=>$message,
                ];
                $code=500;

            }elseif($case->status=='pending'||$case->status=='rejected'){
                $response = [
                    'message'=>'the case must be accepted',
                ];
                $code=500;

            }else{
                if($donationtype_id==5){
                    $donation_items=Donationitem::where('donation_id',$donation->id)->get();
                    $items=Item::where('casee_id',$case->id)->get();
                    $total_amount=$donation->amount;
                    foreach($donation_items as $dt){
                        dd($dt);
                        $item=Item::find($dt->id);
                        $item->amount=$item->amount-$dt->amount;
                        $item->save();
                        $total_amount=$total_amount=$dt->amount;
                    }

                    $case->paied_amount=$case->paied_amount+$donation->amount;
                    $case->remaining_amount=$case->initial_amount-$case->paied_amount;
                    if($case->remaining_amount==0){
                        $case->status='completed';
                    }
                    $code=201;
                    $case->save();
                    $donation->status='accepted';
                    $donation->save();
                    $response = [
                        'message'=>'Done',
                        'case'=>$case,
                        'donation'=>$donation
                    ];
                }
                else{
                    $case->paied_amount=$case->paied_amount+$donation->amount;
                    $case->remaining_amount=$case->initial_amount-$case->paied_amount;
                    if($case->remaining_amount==0){
                        $case->status='completed';
                    }
                    $code=201;
                    $case->save();
                    $donation->status='accepted';
                    $donation->save();
                    $response = [
                        'message'=>'Done',
                        'case'=>$case,
                        'donation'=>$donation
                    ];
                }

            }

        return response($response,$code);

    }
}
