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
                'auctions' => $mazads
            ];
        return response($response, 201);
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
        if ($mazad->status == 'finished') {
            $mazad->delete();
            $response = ['message' => "Your auction is deleted successfully."];
            return response($response, 201);
        }
    }
}