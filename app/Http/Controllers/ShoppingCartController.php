<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;


class ShoppingCartController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $cart = session()->get('cart');

        $line_items = collect($cart)->map(function($item) {
            

            return [
                'price_data' => [
                    'currency' => $item["price"]["currency"],
                    'unit_amount' => $item["price"]["amount"],
                    'product_data' => [
                    'name' => $item["name"],
                    'description' => $item["description"],
                    ],
                ],
                'quantity' => $item["qty"],
                ];
        });

        \Stripe\Stripe::setApiKey('sk_test_pjdpPizIjqvq5MF7tJvb7mZO');

        $checkout_session = \Stripe\Checkout\Session::create([
            'line_items' => $line_items->values()->all(),
            'mode' => 'payment',
            'success_url' => route('shop.index'),
            'cancel_url' => route('shop.index'),
        ]);

        return response('', 409)->header('X-Inertia-Location', $checkout_session->url);

        dd(session()->get('cart'));
        return Inertia::render('Shop/ShoppingCart', [
            'cart' => session()->get('cart')
        ]);
    }
}