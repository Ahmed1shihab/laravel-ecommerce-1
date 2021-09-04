<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use \Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mightAlsoLike = Product::inRandomOrder()->take(4)->get();
        
        return view("cart", [
            'mightAlsoLike' => $mightAlsoLike,
            'discount' => getNumbers()->get('discount'),
            'tax' => getNumbers()->get('tax'),
            'newSubtotal' => getNumbers()->get('newSubtotal'),
            'newTax' => getNumbers()->get('newTax'),
            'newTotal' => getNumbers()->get('newTotal')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $duplicates = Cart::search(function($cartItem, $rowId) use($request) {
            return $cartItem->id === $request->id;
        });

        if ($duplicates->isNotEmpty()) {
            return redirect()->route("cart.index")->with("success_message", "Item Is already In Your Cart!");
        }
        
        Cart::add($request->id, $request->name, 1, $request->price)->associate("App\Models\Product");

        return redirect()->route("cart.index")->with("success_message", "Item Was Add To Your Cart!");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $vaildtor = Validator::make($request->all(), [
            'quantity' => 'required|numeric|between:1,5'
        ]);

        if ($vaildtor->fails()) {
            session()->flash("errors", collect(["Quantity must be between 1 and 5.!"]));
            return response()->json(['success' => false], 400);
        }

        if ($request->quantity > $request->productQuantity) {
            session()->flash("errors", collect(["We currently do not have enough items in stock. You order $request->quantity items and we have $request->productQuantity items"]));
            return response()->json(['success' => false], 400);
        }

        Cart::update($id, $request->quantity);
        session()->flash("success_message", "Quantity was updated successfully!");
        
        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Cart::remove($id);

        return back()->with("success_message", "Item has been removed");
    }

    public function switchToSaveForLater($id)
    {
        $item = Cart::get($id);

        Cart::remove($id);

        $duplicates = Cart::instance("saveForLater")->search(function($cartItem, $rowId) use($id) {
            return $rowId === $id;
        });

        if ($duplicates->isNotEmpty()) {
            return redirect()->route("cart.index")->with("success_message", "Item Is already Saved For Later!");
        }

        Cart::instance("saveForLater")->add($item->id, $item->name, 1, $item->price)->associate("App\Models\Product");

        return redirect()->route("cart.index")->with("success_message", "Item Was Been Saved For Later!");
    }
}
