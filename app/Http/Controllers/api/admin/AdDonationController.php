<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Donation;
use App\Models\Casee;

class AdDonationController extends Controller
{
    public function index(){
        $donations=Donation::all();
        $response = [
            'message'=>'All donations',
            'donations' => $donations
        ];
        return response($response,201);
    }

    public function indexOfCase($caseid){
        $donations=Donation::where('casee_id',$caseid)->get();
        $response = [
            'message'=>'All donations of case',
            'donations' => $donations
        ];
        return response($response,201);
    }

    public function show($id)
    {
        $donation=Donation::findOrFail($id);
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
        }elseif($case->status=='completed'){
                $response = [
                    'message'=>'the case is already completed',
                ];
            }elseif($donationtype_id!=$case->donationtype_id){
                $response = [
                    'message'=>'different donationtypes',
                ];
            }elseif($donation->amount>$case->remaining_amount){
                $message='wrong amount,choose amount less than or equal '.$case->remaining_amount;
                $response = [
                    'message'=>$message,
                ];
            }elseif($case->status=='pending'||$case->status=='rejected'){
                $response = [
                    'message'=>'the case must be accepted',
                ];
            }else{
                $case->paied_amount=$case->paied_amount+$donation->amount;
                $case->remaining_amount=$case->initial_amount-$case->paied_amount;
                if($case->remaining_amount==0){
                    $case->status='completed';
                }

                $case->save();

                $donation->update([
                    'status'=>'accepted'
                ]);

                $response = [
                    'message'=>'Done',
                    'case'=>$case,
                    'donation'=>$donation
                ];
            }

        return response($response,201);

    }
}
