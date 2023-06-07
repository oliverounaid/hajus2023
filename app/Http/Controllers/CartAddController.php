<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Cknow\Money\Money as CKMoney;
use Illuminate\Http\Request;


class CartAddController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {   
        // session key, tähistab array'd, ehk cart = [products]
        $session_key = 'cart.product-'. $request->product['id'];
        if(session()->has($session_key)) {
            $cart_row = session()->get($session_key);
            $cart_row['qty'] = $cart_row['qty'] += $request->qty;
            $cart_row['total'] = CKMoney::EUR($cart_row['qty'] * $cart_row['price']['amount'], 'EUR');
            session()->put($session_key, $cart_row);
            
        } else {
            session()->put(
                $session_key,
                [
                    ...$request->product,
                    'qty' => $request->qty,
                    'total' => CKMoney::EUR($request->qty * $request->product['price']['amount'])
                ]
            );    
        }
        return redirect()->back();
    }
}