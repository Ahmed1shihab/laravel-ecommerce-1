<?php

namespace Tests\Browser;

use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UpdateCartQuantityTest extends DuskTestCase
{
    /** @test */
    public function an_item_in_the_cart_can_update_quantity()
    {
        $product = Product::factory()->create([
            'name' => 'Laptop 1',
            'slug' => 'laptop-1'
        ]);

        $this->browse(function (Browser $browser) use($product) {
            $browser->visit('/')
                    ->visit('/shop/' . $product->slug)
                    ->press('Add to Cart')
                    ->assertPathIs('/cart')
                    ->waitForText('Item Was Add To Your Cart!')
                    ->select('.quantity', 2)
                    ->waitForText('Quantity was updated successfully!');
        });
    }
}
