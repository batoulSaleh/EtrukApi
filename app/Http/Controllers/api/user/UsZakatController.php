<?php

namespace App\Http\Controllers\api\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Zakat;

class UsZakatController extends Controller
{
    public function show(Request $request)
    {
        $gold_price = Zakat::findorfail(1);
        $response = [
            'message'=>trans('api.fetch'),
            'result' => $gold_price,
        ];
        return response($response, 201);
    }
}
