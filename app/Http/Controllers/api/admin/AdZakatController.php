<?php

namespace App\Http\Controllers\api\admin;

use App\Models\Zakat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdZakatController extends Controller
{
    public function update(Request $request)
    {
        $gold_price = Zakat::findorfail(1);
        $request->validate([
            'price_gold21' => 'required|numeric',
            'price_gold24' => 'required|numeric',
        ]);
        $gold_price->update([
            'price_gold21' => $request->price_gold21,
            'price_gold24' => $request->price_gold24,
        ]);
        $response = [
            'message' => 'Prices of gold is updated successfully',
            'result' => $gold_price,
        ];
        return response($response, 201);
    }


    // public function calculate(Request $request)
    // {
    //     $static_price_gold21 = 2525;
    //     $static_price_gold24 = 2886;
    //     $gold21 = $request->gold21;
    //     $gold24 = $request->gold24;
    //     $money = $request->money;

    //     /** Gold 21 */
    //     if ($gold21) // Check the price exists or not
    //     {
    //         if ($gold21 > 87.48) {
    //             $zakat_gold21 = ($gold21*$static_price_gold21* 2.5) / 100;
    //         } else {
    //             $zakat_gold21 = 0;
    //         }
    //     } else {
    //         $zakat_gold21 = 0;
    //     }

    //     /** Gold 24 */
    //     if ($gold24) // Check the price exists or not
    //     {
    //         if ($gold24  >  87.48) {
    //             $zakat_gold24 = ($gold24*$static_price_gold24 * 2.5) / 100;
    //         } else {
    //             $zakat_gold24 = 0;
    //         }
    //     } else {
    //         $zakat_gold24 = 0;
    //     }

    //     /** Money */
    //     if ($money) // Check the price exists or not
    //     {
    //         if ($money  > $static_price_gold21 * 87.48) {
    //             $zakat_money = ($money * 2.5) / 100;
    //         } else {
    //             $zakat_money = 0;
    //         }
    //     } else {
    //         $zakat_money = 0;
    //     }

    //     $response = [
    //         'message' => 'Zakat is calculated successfully.',
    //         'price_gold21' => $request->gold21,
    //         'price_gold24' => $request->gold24,
    //         'money' => $request->money,
    //         'zakat_gold21' => $zakat_gold21,
    //         'zakat_gold24' => $zakat_gold24,
    //         'zakat_money' => $zakat_money,
    //         'total_zakat' => $zakat_gold21 + $zakat_gold24 + $zakat_money,

    //     ];
    //     return response($response, 201);
    // }
}