<?php

use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;

function presentPrice($price) {
  $formatter = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
  return $formatter->formatCurrency($price / 100, 'USD');
}

function presentDate($date)
{
    return Carbon::parse($date)->format('M d, Y');
}

function setActive($requestElement, $element, $output = "active") {
  return request()->$requestElement == $element ? $output : "";
}

function getNumbers() {
  
    $tax = config('cart.tax') / 100;
    $discount = session()->get('coupon')['discount'] ?? 0;
    $code = session()->get('coupon')['name'] ?? null;
    $newSubtotal = (Cart::subtotal()) - $discount;
    if ($newSubtotal < 0) {
      $newSubtotal = 0;
    }
    $newTax = $newSubtotal * $tax;
    $newTotal = ($newSubtotal + $newTax);

    return collect([
        'discount' => $discount,
        'tax' => $tax,
        'code' => $code,
        'newSubtotal' => $newSubtotal,
        'newTax' => $newTax,
        'newTotal' => $newTotal
    ]);
}

function getStockLevel ($quantity) {
  if ($quantity > env('STOCK_THRESHOLD')) {
    $stockLevel = '<div class="badge badge-success">In Stock</div>';
  } elseif ($quantity <= env('STOCK_THRESHOLD') && $quantity > 0) {
      $stockLevel = '<div class="badge badge-warning">Low Stock</div>';
  } else {
      $stockLevel = '<div class="badge badge-danger">Not Available</div>';
  }

  return $stockLevel;
}