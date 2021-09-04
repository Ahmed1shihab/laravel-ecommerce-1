<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pagination = 9;
        $categories = Category::all();

        if (request()->category) {
            $products = Product::with("categories")->whereHas("categories", function ($query) {
                $query->where("slug", request()->category);
            });
            
            $categoryName = optional($categories->where("slug", request()->category)->first())->name;
        } else {
            $products = Product::where('featured', true)->take(12);
            $categoryName = "Featured";
        }

        if (request()->sort == 'low_high') {
            $products = $products->orderBy('price')->paginate($pagination);
        } elseif (request()->sort == "high_low") {
            $products = $products->orderBy('price', 'desc')->paginate($pagination);
        } else {
            $products = $products->paginate($pagination);
        }

        return view("shop", compact("products", "categories", "categoryName"));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $product = Product::where("slug", $slug)->FirstOrFail();
        $mightAlsoLike = Product::where("slug", "!=", $slug)->inRandomOrder()->take(4)->get();

        $stockLevel = getStockLevel($product->quantity);
        

        return view("product", compact("product", "mightAlsoLike", "stockLevel"));
    }

    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|min:3'
        ]);

        $query = $request->input('query');

        // $products = Product::where('name', 'like', "%$query%")->paginate(10);

        $products = Product::search($query)->paginate(10);

        return view('search-results', compact("products"));
    }

    public function searchAlgolia(Request $request)
    {
        return view('search-results-algolia');
    }
}
