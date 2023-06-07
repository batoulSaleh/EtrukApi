<?php

namespace App\Http\Controllers\api\admin;

use Carbon\Carbon;
use App\Models\Mazad;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdMazadController extends Controller
{
    public function index()
    {
        $mazads = Mazad::with('mazadimage')->get();
        $response =
            [
                'message' => "all auctions.",
                'auctions' => $mazads,
                'count' =>count($mazads)
            ];
        return response($response, 201);
    }

    public function show($id)
    {
        $mazad=Mazad::find($id);
        $response = [
            'message'=>'specific mazad with id',
            'mazad' => $mazad
        ];
        return response($response,201);
    }


    public function update(Request $request, string $id)
    {
        // $mazad = Mazad::find($id);
        $mazad = Mazad::with('mazadimage')->where('id', $id)->first();
        $request->validate([
            'status' => 'required|in:pending,accepted,rejected,finished',
        ]);
        $mazad->update(
            [
                'status' => $request->status,
            ]
        );
        if ($mazad->status == 'rejected') {
            $response = ['message' => "Your auction can't be published."];
            return response($response, 201);
        }
        elseif($mazad->status == 'finished') {
            $response = ['message' => "Your auction is finished "];
            return response($response, 201);
        }else {
            $response =
                [
                    'message' => "Your auction is published successfully.",
                    'auction' => $mazad,
                ];
            return response($response, 201);
        }
    }

    public function destroy($id)
    {
        $mazad = Mazad::findOrFail($id);
            $mazad->delete();
            $response = ['message' => "Your auction is deleted successfully."];
            return response($response, 201);
    }
}