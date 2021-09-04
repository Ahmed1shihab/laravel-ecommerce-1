<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewCategoryPageTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function category_page_show_the_correct_products()
    {
        $laptop1 = Product::factory()->create(['name' => "Laptop 1"]);
        $laptop2 = Product::factory()->create(['name' => "Laptop 2"]);

        $laptopCategory = Category::create([
            'name' => 'laptops',
            'slug' => 'laptops'
        ]);

        $laptop1->categories()->attach($laptopCategory->id);
        $laptop2->categories()->attach($laptopCategory->id);

        $response = $this->get('/shop?category=laptops');

        $response->assertSee('Laptop 1');
        $response->assertSee('Laptop 2');
    }

    /** @test */
    public function category_page_does_not_show_products_in_another_category()
    {
        $laptop1 = Product::factory()->create(['name' => "Laptop 1"]);
        $laptop2 = Product::factory()->create(['name' => "Laptop 2"]);

        $laptopCategory = Category::create([
            'name' => 'laptops',
            'slug' => 'laptops'
        ]);

        $laptop1->categories()->attach($laptopCategory->id);
        $laptop2->categories()->attach($laptopCategory->id);

        $desktop1 = Product::factory()->create(['name' => "Desktop 1"]);
        $desktop2 = Product::factory()->create(['name' => "Desktop 2"]);

        $desktopCategory = Category::create([
            'name' => 'desktops',
            'slug' => 'desktops'
        ]);

        $desktop1->categories()->attach($desktopCategory->id);
        $desktop2->categories()->attach($desktopCategory->id);

        $response = $this->get('/shop?category=laptops');

        $response->assertStatus(200);
        $response->assertDontSee('Desktop 1');
        $response->assertDontSee('Desktop 2');
    }
}
