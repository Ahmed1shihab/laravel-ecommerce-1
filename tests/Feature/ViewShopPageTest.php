<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewShopPageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function shop_page_loads_correctly()
    {
        // Arrange
        
        // Act
        $response = $this->get('/shop');
        
        // Assert
        $response->assertStatus(200);
        $response->assertSee('Featured');
        
    }

    /** @test */
    public function featured_product_is_visible()
    {
        // Arrange
        $featuredProduct = Product::factory()->create([
            'featured' => true,
            'price' => 149999
        ]);
        
        // Act
        $response = $this->get('/shop');
        
        // Assert
        $response->assertStatus(200);
        $response->assertSee($featuredProduct->name);
        $response->assertSee('$1,499.99');
        
    }

    /** @test */
    public function not_featured_product_is_not_visible()
    {
        // Arrange
        $notFeaturedProduct = Product::factory()->create([
            'featured' => false,
            'price' => 149999
        ]);
        
        // Act
        $response = $this->get('/shop');
        
        // Assert
        $response->assertStatus(200);
        $response->assertDontSee($notFeaturedProduct->name);
        $response->assertDontSee('$1,499.99');
        
    }

    /** @test */
    public function pagination_for_products_works()
    {
        // Products For Page 1
        for ($i=11; $i < 20; $i++) { 
            Product::factory()->create([
                'featured' => true,
                'name' => "Product " . $i,
            ]);
        }

        // Products For Page 2
        for ($i=21; $i < 30; $i++) { 
            Product::factory()->create([
                'featured' => true,
                'name' => "Product " . $i,
            ]);
        }

        // Acct for page 1
        $response = $this->get('/shop');

        // Assert for page 1
        $response->assertSee('Product 11');
        $response->assertSee('Product 19');

        // Acct for page 2
        $response = $this->get('/shop?page=2');

        // Assert for page 2
        $response->assertSee('Product 21');
        $response->assertSee('Product 29');
    }

    /** @test */
    public function sort_price_low_to_high()
    {
        Product::factory()->create([
            'featured' => true,
            'name' => 'Product High',
            'price' => 2000
        ]);
        
        Product::factory()->create([
            'featured' => true,
            'name' => 'Product Middle',
            'price' => 1500
        ]);

        Product::factory()->create([
            'featured' => true,
            'name' => 'Product Low',
            'price' => 1000
        ]);

        $response = $this->get('/shop?sort=low_high');

        $response->assertStatus(200);
        $response->assertSeeInOrder(['Product Low', 'Product Middle', 'Product High']);
    }

    /** @test */
    public function sort_price_high_to_low()
    {
        Product::factory()->create([
            'featured' => true,
            'name' => 'Product High',
            'price' => 2000
        ]);
        
        Product::factory()->create([
            'featured' => true,
            'name' => 'Product Middle',
            'price' => 1500
        ]);

        Product::factory()->create([
            'featured' => true,
            'name' => 'Product Low',
            'price' => 1000
        ]);

        $response = $this->get('/shop?sort=high_low');

        $response->assertStatus(200);
        $response->assertSeeInOrder(['Product High', 'Product Middle', 'Product Low']);
    }
}
