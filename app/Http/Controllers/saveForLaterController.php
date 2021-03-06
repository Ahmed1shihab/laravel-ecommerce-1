<?php

namespace App\Http\Controllers;

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

class saveForLaterController extends Controller
{
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Cart::instance("saveForLater")->remove($id);

        return back()->with("success_message", "Item has been removed");
    }

    public function switchToCart($id)
    {
        $item = Cart::instance("saveForLater")->get($id);

        Cart::remove($id);

        $duplicates = Cart::instance("default")->search(function($cartItem, $rowId) use($id) {
            return $rowId === $id;
        });

        if ($duplicates->isNotEmpty()) {
            return redirect()->route("cart.index")->with("success_message", "Item Is already Exist In Cart!");
        }

        Cart::instance("default")->add($item->id, $item->name, 1, $item->price)->associate("App\Models\Product");

        return redirect()->route("cart.index")->with("success_message", "Item Was Been Moved To Cart!");
    }
}
