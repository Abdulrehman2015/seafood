<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartIsolationTest extends TestCase
{
    use RefreshDatabase;

    protected Category $category;
    protected Product $product1;
    protected Product $product2;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = Category::create([
            'name'        => 'Fresh Seafood',
            'slug'        => 'fresh-seafood',
            'is_active'   => true,
        ]);

        $this->product1 = Product::create([
            'name'                 => 'Atlantic Salmon Fillet',
            'slug'                 => 'atlantic-salmon-fillet',
            'sku'                  => 'SAL-01',
            'category_id'          => $this->category->id,
            'retail_price'         => 25.90,
            'walkin_price'         => 22.00,
            'wholesale_price'      => 19.00,
            'retail_moq'           => 1,
            'walkin_moq'           => 1,
            'wholesale_moq'        => 5,
            'stock_quantity'       => 50,
            'unit'                 => 'pack',
            'is_walkin_available'  => true,
            'is_active'            => true,
        ]);

        $this->product2 = Product::create([
            'name'                 => 'Flower Crab',
            'slug'                 => 'flower-crab',
            'sku'                  => 'CRAB-01',
            'category_id'          => $this->category->id,
            'retail_price'         => 16.90,
            'walkin_price'         => 15.00,
            'wholesale_price'      => 12.00,
            'retail_moq'           => 1,
            'walkin_moq'           => 1,
            'wholesale_moq'        => 5,
            'stock_quantity'       => 50,
            'unit'                 => '500g',
            'is_walkin_available'  => true,
            'is_active'            => true,
        ]);

        $this->user = User::create([
            'name'           => 'John Doe',
            'email'          => 'john@example.com',
            'password'       => bcrypt('password'),
            'customer_group' => 'retail',
            'phone'          => '+60123456789',
        ]);
    }

    public function test_products_added_from_shop_go_to_retail_delivery_cart(): void
    {
        $response = $this->actingAs($this->user)->postJson(route('cart.add'), [
            'product_id' => $this->product1->id,
            'quantity'   => 2,
            'group'      => 'retail',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'count'   => 2,
            'total'   => 51.80,
        ]);

        $this->assertDatabaseHas('carts', [
            'user_id'        => $this->user->id,
            'product_id'     => $this->product1->id,
            'quantity'       => 2,
            'customer_group' => 'retail',
        ]);
    }

    public function test_products_added_from_walkin_go_to_walkin_cart(): void
    {
        $response = $this->actingAs($this->user)->postJson(route('cart.add'), [
            'product_id' => $this->product2->id,
            'quantity'   => 3,
            'group'      => 'walkin',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'count'   => 3,
            'total'   => 45.00,
        ]);

        $this->assertDatabaseHas('carts', [
            'user_id'        => $this->user->id,
            'product_id'     => $this->product2->id,
            'quantity'       => 3,
            'customer_group' => 'walkin',
        ]);
    }

    public function test_visiting_walkin_does_not_corrupt_header_cart_on_products_page(): void
    {
        // 1. User visits walkin menu & adds 7 walkin items
        $this->actingAs($this->user)->get(route('walkin.shop'));
        $this->actingAs($this->user)->postJson(route('cart.add'), [
            'product_id' => $this->product2->id,
            'quantity'   => 7,
            'group'      => 'walkin',
        ]);

        // 2. User navigates to regular products page
        $productsPage = $this->actingAs($this->user)->get(route('shop.index'));
        $productsPage->assertStatus(200);

        // 3. Header cart count on regular pages queries route('cart.count')
        $countResponse = $this->actingAs($this->user)->getJson(route('cart.count'));
        $countResponse->assertStatus(200);
        $countResponse->assertJson([
            'count' => 0, // Header cart shows 0, NOT 7!
        ]);

        // 4. User adds 1 retail product from the products page
        $addRetail = $this->actingAs($this->user)->postJson(route('cart.add'), [
            'product_id' => $this->product1->id,
            'quantity'   => 1,
            'group'      => 'retail',
        ]);
        $addRetail->assertJson([
            'success' => true,
            'count'   => 1,
        ]);

        // 5. Header cart count is now 1
        $countResponseAfter = $this->actingAs($this->user)->getJson(route('cart.count'));
        $countResponseAfter->assertJson([
            'count' => 1,
        ]);

        // 6. Check database isolation
        $this->assertDatabaseHas('carts', [
            'user_id'        => $this->user->id,
            'product_id'     => $this->product2->id,
            'quantity'       => 7,
            'customer_group' => 'walkin',
        ]);

        $this->assertDatabaseHas('carts', [
            'user_id'        => $this->user->id,
            'product_id'     => $this->product1->id,
            'quantity'       => 1,
            'customer_group' => 'retail',
        ]);
    }

    public function test_delivery_cart_page_and_walkin_cart_page_are_isolated(): void
    {
        // Create 1 retail cart item and 1 walkin cart item for user
        Cart::create([
            'user_id'        => $this->user->id,
            'product_id'     => $this->product1->id,
            'quantity'       => 1,
            'customer_group' => 'retail',
        ]);

        Cart::create([
            'user_id'        => $this->user->id,
            'product_id'     => $this->product2->id,
            'quantity'       => 7,
            'customer_group' => 'walkin',
        ]);

        // Regular delivery cart page (/cart) should only show Atlantic Salmon Fillet
        $deliveryCart = $this->actingAs($this->user)->get(route('cart.index'));
        $deliveryCart->assertStatus(200);
        $deliveryCart->assertSee('Atlantic Salmon Fillet');
        $deliveryCart->assertDontSee('Flower Crab');

        // Walk-in cart page (/walkin/cart) should only show Flower Crab
        $walkinCart = $this->actingAs($this->user)->get(route('walkin.cart'));
        $walkinCart->assertStatus(200);
        $walkinCart->assertSee('Flower Crab');
        $walkinCart->assertDontSee('Atlantic Salmon Fillet');
    }

    public function test_walkin_menu_does_not_show_items_added_from_product_page(): void
    {
        // 1. User adds 2 items from regular shop (retail delivery)
        $this->actingAs($this->user)->postJson(route('cart.add'), [
            'product_id' => $this->product1->id,
            'quantity'   => 2,
            'group'      => 'retail',
        ]);

        // 2. User visits Walk-in menu (/en/walkin)
        $walkinMenu = $this->actingAs($this->user)->get(route('walkin.shop'));
        $walkinMenu->assertStatus(200);

        // 3. Walk-in view data must have cartCount = 0 and cartTotals total = 0
        $walkinMenu->assertViewHas('cartCount', 0);
        $walkinMenu->assertViewHas('cartTotals', fn($totals) => ($totals['total'] ?? null) == 0);

        // 4. Querying walkin cart count endpoint returns 0
        $walkinCountResponse = $this->actingAs($this->user)->getJson(route('cart.count', ['group' => 'walkin']));
        $walkinCountResponse->assertStatus(200);
        $walkinCountResponse->assertJson([
            'count' => 0,
            'total' => 0,
        ]);
    }
}

