<?php

namespace App\Http\Controllers\api\user;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsZakatController extends Controller
{
    public function calculate(Request $request)
    {
        $static_price_gold21 = 2525;
        $static_price_gold24 = 2886;
        $price_gold21 = $request->gold21;
        $price_gold24 = $request->gold24;
        $money = $request->money;

        /** Gold 21 */
        if ($price_gold21) // Check the price exists or not
        {
            if ($price_gold21  > $static_price_gold21 * 87.48) {
                $zakat_gold21 = ($price_gold21 * 2.5) / 100;
            } else {
                $zakat_gold21 = 0;
            }
        } else {
            $zakat_gold21 = 0;
        }

        /** Gold 24 */
        if ($price_gold24) // Check the price exists or not
        {
            if ($price_gold24  > $static_price_gold24 * 87.48) {
                $zakat_gold24 = ($price_gold24 * 2.5) / 100;
            } else {
                $zakat_gold24 = 0;
            }
        } else {
            $zakat_gold24 = 0;
        }

        /** Money */
        if ($money) // Check the price exists or not
        {
            if ($money  > $static_price_gold21 * 87.48) {
                $zakat_money = ($money * 2.5) / 100;
            } else {
                $zakat_money = 0;
            }
        } else {
            $zakat_money = 0;
        }

        $response = [
            'message' => 'Zakat is calculated successfully.',
            'price_gold21' => $request->gold21,
            'price_gold24' => $request->gold24,
            'money' => $request->money,
            'zakat_gold21' => $zakat_gold21,
            'zakat_gold24' => $zakat_gold24,
            'zakat_money' => $zakat_money,
            'total_zakat' => $zakat_gold21 + $zakat_gold24 + $zakat_money,

        ];
        return response($response, 201);
    }
}