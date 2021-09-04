
<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ConfirmationController;
use App\Http\Controllers\CouponsController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\saveForLaterController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UsersController;
use App\Mail\OrderPlaced;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use TCG\Voyager\Facades\Voyager;

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [LandingPageController::class, "index"])->name("landing-page");

Route::get('/shop', [ShopController::class, "index"])->name("shop.index");
Route::get('/shop/{slugName}', [ShopController::class, "show"])->name("shop.show");

Route::get('/search', [ShopController::class, "search"])->name("search");
Route::get('/search-algolia', [ShopController::class, "searchAlgolia"])->name("search-algolia");

Route::get('/cart', [CartController::class, "index"])->name("cart.index");
Route::post('/cart', [CartController::class, "store"])->name("cart.store");
Route::patch('/cart/{product}', [CartController::class, "update"])->name("cart.update");
Route::delete('/cart/{product}', [CartController::class, "destroy"])->name("cart.destroy");

Route::post('/cart/switchToSaveForLater/{product}', [CartController::class, "switchToSaveForLater"])->name("cart.switchToSaveForLater");
Route::delete('/saveforlater/{product}', [saveForLaterController::class, "destroy"])->name("saveForLater.destroy");
Route::post('/switchtocart/{product}', [saveForLaterController::class, "switchToCart"])->name("saveForLater.switchToCart");

Route::post('/coupon', [CouponsController::class, 'store'])->name('coupon.store');
Route::delete('/coupon', [CouponsController::class, 'destroy'])->name('coupon.destroy');

Route::post("/checkout", [CheckoutController::class, "store"])->name("checkout.store");

Route::get("/guestCheckout", [CheckoutController::class, "index"])->name("guestCheckout.index");

Route::get("/thankyou", [ConfirmationController::class, "index"])->name("confirmation.index");

Route::middleware('auth')->group(function () {
    Route::get("/checkout", [CheckoutController::class, "index"])->name("checkout.index");

    Route::get('/my-profile', [UsersController::class, 'edit'])->name('users.edit');
    Route::patch('/my-profile', [UsersController::class, 'update'])->name('users.update');

    Route::get('/my-orders', [OrdersController::class, 'index'])->name('orders.index');
    Route::get('/my-orders/{order}', [OrdersController::class, 'show'])->name('orders.show');
});

Route::get('/email', function() {
    $order = Order::find(1);

    return new OrderPlaced($order);
});