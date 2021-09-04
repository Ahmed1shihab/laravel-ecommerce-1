<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $orders = auth()->user()->orders; // n + 1 issues
        $orders = auth()->user()->orders()->with('products')->get(); // fix n + 1 issues

        return view('my-orders', compact('orders'));
    }

    /**
     * Display the specified resource.
     *
     * @param  Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        if (auth()->id() != $order->user_id) {
            return back()->withErrors("You don't have access to this!");
        }
        
        $products = $order->products;

        return view('my-order', compact('order', 'products'));
    }
}
