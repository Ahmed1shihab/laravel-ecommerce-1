<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewProductPageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_view_product_details()
    {
        $product = Product::factory()->create([
            'name' => 'Laptop 1',
            'slug' => 'laptop-1',
            'details' => '13 inch, 3 TB SSD, 32GB RAM',
            'price' => 349999,
            'description' => 'This is a description for laptop 1',
        ]);

        $response = $this->get('/shop/' . $product->slug);

        $response->assertStatus(200);
        $response->assertSee('13 inch, 3 TB SSD, 32GB RAM');
        $response->assertSee('3,499.99');
        $response->assertSee('This is a description for laptop 1');
    }
    
    /** @test */
    public function sotck_level_high()
    {
        $product = Product::factory()->create(['quantity' => 9]);

        $response = $this->get('/shop/' . $product->slug);

        $response->assertStatus(200);
        $response->assertSee('In Stock');
    }

    /** @test */
    public function sotck_level_low()
    {
        $product = Product::factory()->create(['quantity' => 2]);

        $response = $this->get('/shop/' . $product->slug);

        $response->assertStatus(200);
        $response->assertSee('Low Stock');
    }

    /** @test */
    public function sotck_level_none()
    {
        $product = Product::factory()->create(['quantity' => 0]);

        $response = $this->get('/shop/' . $product->slug);

        $response->assertStatus(200);
        $response->assertSee('Not Available');
    }

    /** @test */
    public function show_related_products()
    {
        $laptop1 = Product::factory()->create(['name' =>'Laptop 1']);
        $laptop2 = Product::factory()->create(['name' =>'Laptop 2']);

        $response = $this->get('/shop/' . $laptop1->slug);

        $response->assertStatus(200);
        $response->assertSee('Laptop 2');
        $response->assertViewHas('mightAlsoLike');
    }
}
